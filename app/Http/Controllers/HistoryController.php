<?php

namespace App\Http\Controllers;

use App\Models\{History, Category};
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Support\VideoEmbed;

class HistoryController extends Controller
{
    private const COVER_MAX_FILE_SIZE_KB = 3072;
    private const COVER_MIN_WIDTH = 800;
    private const COVER_MIN_HEIGHT = 600;
    private const COVER_MAX_WIDTH = 3200;
    private const COVER_MAX_HEIGHT = 3200;

    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy', 'addComment']);
        $this->authorizeResource(History::class, 'historia');
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $selectedEra = $request->query('era');
        $selectedCategory = $request->query('category');
        $selectedProtagonist = $request->query('protagonist');

        $categories = Category::orderBy('name')->get(['id', 'name', 'slug']);
        $eras = cache()->remember('histories:eras', 600, fn () => History::whereNotNull('era')->distinct()->orderBy('era')->pluck('era'));
        $leadingFigures = cache()->remember('histories:leading_figures', 600, fn () => History::whereNotNull('leading_figure')->distinct()->orderBy('leading_figure')->pluck('leading_figure'));

        $categoryIdsBySlug = $categories->pluck('id', 'slug');

        $applyFilters = function ($query) use ($q, $selectedEra, $selectedProtagonist, $selectedCategory, $categoryIdsBySlug) {
            if ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('excerpt', 'like', "%{$q}%")
                        ->orWhere('content', 'like', "%{$q}%");
                });
            }

            if ($selectedEra) {
                $query->where('era', $selectedEra);
            }

            if ($selectedProtagonist) {
                $query->where('leading_figure', $selectedProtagonist);
            }

            if ($selectedCategory && $categoryIdsBySlug->has($selectedCategory)) {
                $query->where('category_id', $categoryIdsBySlug->get($selectedCategory));
            }
        };

        $itemsQuery = History::with('category', 'author');
        $applyFilters($itemsQuery);
        $items = $itemsQuery->latest('published_at')->paginate(12)->appends($request->query());

        $featuredStoriesQuery = History::with('category', 'author')->whereNotNull('cover_path');
        $applyFilters($featuredStoriesQuery);
        $featuredStories = $featuredStoriesQuery->latest('published_at')->take(3)->get();

        $hasActiveFilters = (bool) ($q || $selectedCategory || $selectedEra || $selectedProtagonist);

        if ($featuredStories->isEmpty() && ! $hasActiveFilters) {
            $featuredStories = History::with('category', 'author')
                ->whereNotNull('cover_path')
                ->latest('published_at')
                ->take(3)
                ->get();
        }

        $spotlightStory = $featuredStories->first() ?? $items->first();

        $activeFilters = collect();
        if ($q) {
            $activeFilters->put('Búsqueda', $q);
        }
        if ($selectedCategory) {
            $categoryLabel = optional($categories->firstWhere('slug', $selectedCategory))->name;
            if ($categoryLabel) {
                $activeFilters->put('Categoría', $categoryLabel);
            }
        }
        if ($selectedEra) {
            $activeFilters->put('Época', $selectedEra);
        }
        if ($selectedProtagonist) {
            $activeFilters->put('Protagonista', $selectedProtagonist);
        }

        $totalStories = History::count();

        return view('historia.index', [
            'items' => $items,
            'q' => $q,
            'categories' => $categories,
            'eras' => $eras,
            'leadingFigures' => $leadingFigures,
            'selectedCategory' => $selectedCategory,
            'selectedEra' => $selectedEra,
            'selectedProtagonist' => $selectedProtagonist,
            'featuredStories' => $featuredStories,
            'spotlightStory' => $spotlightStory,
            'activeFilters' => $activeFilters,
            'hasActiveFilters' => $hasActiveFilters,
            'totalStories' => $totalStories,
        ]);
    }

    public function create()
    {
        $cats = Category::orderBy('name')->get();
        $eras = cache()->remember('histories:eras', 600, fn () => History::whereNotNull('era')->distinct()->orderBy('era')->pluck('era'));
        $leadingFigures = cache()->remember('histories:leading_figures', 600, fn () => History::whereNotNull('leading_figure')->distinct()->orderBy('leading_figure')->pluck('leading_figure'));

        return view('historia.create', compact('cats', 'eras', 'leadingFigures'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'title' => 'required|max:160',
            'category_id' => 'nullable|exists:categories,id',
            'excerpt' => 'nullable|max:500',
            'content' => 'required',
            'era' => 'nullable|max:120',
            'leading_figure' => 'nullable|max:160',
            'cover' => 'nullable|image|max:' . self::COVER_MAX_FILE_SIZE_KB . '|dimensions:min_width=' . self::COVER_MIN_WIDTH . ',min_height=' . self::COVER_MIN_HEIGHT . ',max_width=' . self::COVER_MAX_WIDTH . ',max_height=' . self::COVER_MAX_HEIGHT,
            'video_url' => ['nullable', 'url', 'max:255', function ($attribute, $value, $fail) {
                if (! VideoEmbed::toEmbedUrl($value)) {
                    $fail('El video debe ser un enlace válido de YouTube o Vimeo.');
                }
            }],
        ]);

        $data['user_id'] = auth()->id();
        $data['slug'] = Str::slug($data['title']) . '-' . uniqid();

        if ($r->hasFile('cover')) {
            $data['cover_path'] = $this->storeCover($r->file('cover'));
        }

        $data['published_at'] = now();

        $history = History::create($data);

        return redirect()->route('historia.show', $history)->with('ok', 'Creado');
    }

    public function show(History $historia)
    {
        $historia->load(['author', 'category', 'comments.author']);

        return view('historia.show', compact('historia'));
    }

    public function addComment(Request $request, History $historia)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $historia->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        return back()->with('ok', 'Comentario publicado.');
    }

    public function edit(History $historia)
    {
        $cats = Category::orderBy('name')->get();
        $eras = cache()->remember('histories:eras', 600, fn () => History::whereNotNull('era')->distinct()->orderBy('era')->pluck('era'));
        $leadingFigures = cache()->remember('histories:leading_figures', 600, fn () => History::whereNotNull('leading_figure')->distinct()->orderBy('leading_figure')->pluck('leading_figure'));

        return view('historia.edit', compact('historia', 'cats', 'eras', 'leadingFigures'));
    }

    public function update(Request $r, History $historia)
    {
        $data = $r->validate([
            'title' => 'required|max:160',
            'category_id' => 'nullable|exists:categories,id',
            'excerpt' => 'nullable|max:500',
            'content' => 'required',
            'era' => 'nullable|max:120',
            'leading_figure' => 'nullable|max:160',
            'cover' => 'nullable|image|max:' . self::COVER_MAX_FILE_SIZE_KB . '|dimensions:min_width=' . self::COVER_MIN_WIDTH . ',min_height=' . self::COVER_MIN_HEIGHT . ',max_width=' . self::COVER_MAX_WIDTH . ',max_height=' . self::COVER_MAX_HEIGHT,
            'video_url' => ['nullable', 'url', 'max:255', function ($attribute, $value, $fail) {
                if (! VideoEmbed::toEmbedUrl($value)) {
                    $fail('El video debe ser un enlace válido de YouTube o Vimeo.');
                }
            }],
        ]);

        if ($r->hasFile('cover')) {
            $this->deleteCover($historia->cover_path);
            $data['cover_path'] = $this->storeCover($r->file('cover'));
        }

        $historia->update($data);

        return back()->with('ok', 'Actualizado');
    }

    public function destroy(History $historia)
    {
        $this->deleteCover($historia->cover_path);
        $historia->delete();

        return redirect()->route('historia.index')->with('ok', 'Eliminado');
    }

    protected function storeCover(UploadedFile $file): string
    {
        $image = Image::make($file)
            ->orientate()
            ->resize(1920, 1080, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode('jpg', 85);

        $filename = 'cover-' . Str::uuid() . '.jpg';
        $path = 'covers/' . $filename;

        Storage::disk('public')->put($path, (string) $image);

        return $path;
    }

    protected function deleteCover(?string $path): void
    {
        if (! $path) {
            return;
        }

        Storage::disk('public')->delete(ltrim($path, '/'));
    }
}