<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $r)
    {
        $q = $r->get('q');
        $news = News::when($q, fn($query)=>$query->where('title','like',"%$q%"))
            ->latest('published_at')->paginate(10);
        return view('noticias.index', compact('news','q'));
    }

    public function create()
    {
        return view('noticias.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'title' => 'required|max:160',
            'body' => 'required',
            'cover' => 'nullable|image|max:5120',
        ]);

        $payload = collect($data)->except('cover')->toArray();
        $payload['user_id'] = $r->user()->id;
        $payload['slug'] = Str::slug($payload['title']).'-'.Str::random(6);
        $payload['published_at'] = now();

        if ($r->hasFile('cover')) {
            $payload['cover_path'] = $r->file('cover')->store('news', 'public');
        }

        News::create($payload);
        return redirect()->route('noticias.index')->with('ok','Noticia publicada');
    }

    public function show(News $noticia)
    {
        return view('noticias.show', compact('noticia'));
    }

    public function edit(News $noticia)
    {
        return view('noticias.edit', compact('noticia'));
    }

    public function update(Request $r, News $noticia)
    {
        $data = $r->validate([
            'title' => 'required|max:160',
            'body' => 'required',
            'cover' => 'nullable|image|max:5120',
        ]);

        $payload = collect($data)->except('cover')->toArray();

        if ($r->hasFile('cover')) {
            $payload['cover_path'] = $r->file('cover')->store('news', 'public');
        }

        $noticia->update($payload);
        return back()->with('ok','Noticia actualizada');
    }

    public function destroy(News $noticia)
    {
        $noticia->delete();
        return redirect()->route('noticias.index')->with('ok','Noticia eliminada');
    }
}