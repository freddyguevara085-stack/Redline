<?php

namespace App\Http\Controllers;

use App\Models\LibraryItem;
use App\Support\VideoEmbed;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class LibraryItemController extends Controller
{
    private const COVER_MAX_FILE_SIZE_KB = 3072;
    private const COVER_MIN_WIDTH = 800;
    private const COVER_MIN_HEIGHT = 600;
    private const COVER_MAX_WIDTH = 3200;
    private const COVER_MAX_HEIGHT = 3200;

    public function index(Request $r)
    {
        $type = $r->get('type');
        $q = $r->get('q');
        $items = LibraryItem::with('author')
            ->when($type, fn($query)=>$query->where('type',$type))
            ->when($q, fn($query)=>$query->where('title','like',"%$q%"))
            ->latest()->paginate(12)->appends($r->query());
        return view('biblioteca.index', compact('items','type','q'));
    }

    public function create()
    {
        return view('biblioteca.create');
    }

    public function store(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'title'=>'required|max:160',
            'type' =>'required|in:pdf,image,video,link',
            'description'=>'nullable|max:1000',
            'cover' => 'nullable|image|max:' . self::COVER_MAX_FILE_SIZE_KB . '|dimensions:min_width=' . self::COVER_MIN_WIDTH . ',min_height=' . self::COVER_MIN_HEIGHT . ',max_width=' . self::COVER_MAX_WIDTH . ',max_height=' . self::COVER_MAX_HEIGHT,
            'video_url' => ['nullable', 'url', 'max:255', function ($attribute, $value, $fail) {
                if ($value && ! VideoEmbed::toEmbedUrl($value)) {
                    $fail('El video debe ser un enlace válido de YouTube o Vimeo.');
                }
            }],
            'video_caption' => 'nullable|string|max:800',
            'external_url'=>'nullable|url|max:255|required_if:type,link',
            'file'=>'nullable|file|max:51200'
        ], [
            'external_url.required_if' => 'Debes proporcionar el enlace externo para este recurso.',
        ]);

        $validator->after(function ($validator) use ($r) {
            $type = $r->input('type');
            $hasFile = $r->hasFile('file');

            if ($type === 'video' && ! $hasFile && ! $r->filled('video_url')) {
                $validator->errors()->add('video_url', 'Para un recurso de video debes subir un archivo o proporcionar un enlace compatible.');
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $data = $validator->validated();
        $data['user_id'] = auth()->id();
        $data['video_caption'] = $data['video_caption'] ? trim($data['video_caption']) : null;

        if ($data['type'] !== 'video') {
            $data['video_url'] = null;
            $data['video_caption'] = null;
        }

        if ($data['type'] !== 'link') {
            $data['external_url'] = null;
        }

        if ($r->hasFile('cover')) {
            $data['cover_path'] = $this->storeCover($r->file('cover'));
        }

        if($r->hasFile('file')){
            $data['file_path'] = $r->file('file')->store('library','public');
        }

        $item = LibraryItem::create($data);
        return redirect()->route('biblioteca.show',$item)->with('ok','Recurso creado');
    }

    public function show(LibraryItem $biblioteca)
    {
        $biblioteca->load('author','comments.author');
        return view('biblioteca.show', ['item'=>$biblioteca]);
    }

    public function edit(LibraryItem $biblioteca)
    {
        return view('biblioteca.edit', ['item'=>$biblioteca]);
    }

    public function update(Request $r, LibraryItem $biblioteca)
    {
        $validator = Validator::make($r->all(), [
            'title'=>'required|max:160',
            'type' =>'required|in:pdf,image,video,link',
            'description'=>'nullable|max:1000',
            'cover' => 'nullable|image|max:' . self::COVER_MAX_FILE_SIZE_KB . '|dimensions:min_width=' . self::COVER_MIN_WIDTH . ',min_height=' . self::COVER_MIN_HEIGHT . ',max_width=' . self::COVER_MAX_WIDTH . ',max_height=' . self::COVER_MAX_HEIGHT,
            'video_url' => ['nullable', 'url', 'max:255', function ($attribute, $value, $fail) {
                if ($value && ! VideoEmbed::toEmbedUrl($value)) {
                    $fail('El video debe ser un enlace válido de YouTube o Vimeo.');
                }
            }],
            'video_caption' => 'nullable|string|max:800',
            'external_url'=>'nullable|url|max:255|required_if:type,link',
            'file'=>'nullable|file|max:51200'
        ], [
            'external_url.required_if' => 'Debes proporcionar el enlace externo para este recurso.',
        ]);

        $validator->after(function ($validator) use ($r, $biblioteca) {
            $type = $r->input('type');
            $hasFile = $r->hasFile('file');
            $hasExistingFile = (bool) $biblioteca->file_path;

            if ($type === 'video' && ! $hasFile && ! $r->filled('video_url') && ! $hasExistingFile) {
                $validator->errors()->add('video_url', 'Para un recurso de video debes subir un archivo o proporcionar un enlace compatible.');
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $data = $validator->validated();
        $data['video_caption'] = $data['video_caption'] ? trim($data['video_caption']) : null;

        if ($data['type'] !== 'video') {
            $data['video_url'] = null;
            $data['video_caption'] = null;
        }

        if ($data['type'] !== 'link') {
            $data['external_url'] = null;
        }

        if($r->hasFile('cover')) {
            $this->deleteCover($biblioteca->cover_path);
            $data['cover_path'] = $this->storeCover($r->file('cover'));
        }

        if($r->hasFile('file')){
            if($biblioteca->file_path) Storage::disk('public')->delete(ltrim($biblioteca->file_path, '/'));
            $data['file_path'] = $r->file('file')->store('library','public');
        }
        $biblioteca->update($data);
        return back()->with('ok','Recurso actualizado');
    }

    public function destroy(LibraryItem $biblioteca)
    {
        $this->deleteCover($biblioteca->cover_path);
        if($biblioteca->file_path) Storage::disk('public')->delete(ltrim($biblioteca->file_path, '/'));
        $biblioteca->delete();
        return redirect()->route('biblioteca.index')->with('ok','Recurso eliminado');
    }

    // Comentarios
    public function addComment(Request $r, LibraryItem $biblioteca) {
        $r->validate(['body'=>'required|max:1000']);
        $biblioteca->comments()->create([
            'user_id'=>auth()->id(),
            'body'=>$r->body,
        ]);
        return back()->with('ok','¡Comentario publicado!');
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

        $filename = 'library-cover-' . Str::uuid() . '.jpg';
        $path = 'library/covers/' . $filename;

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