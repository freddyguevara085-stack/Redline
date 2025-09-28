<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('author')->orderBy('start_at')->get();
        return view('calendario.index', compact('events'));
    }

    public function create()
    {
        return view('calendario.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'title'=>'required|max:160',
            'description'=>'nullable|max:1000',
            'start_at'=>'required|date',
            'end_at'=>'nullable|date|after_or_equal:start_at',
            'location'=>'nullable|max:255'
        ]);
        $data['user_id'] = auth()->id();
        Event::create($data);
        return redirect()->route('calendario.index')->with('ok','Evento creado');
    }

    public function show(Event $calendario)
    {
        $calendario->load('author');
        return view('calendario.show', compact('calendario'));
    }

    public function edit(Event $calendario)
    {
        return view('calendario.edit', compact('calendario'));
    }

    public function update(Request $r, Event $calendario)
    {
        $data = $r->validate([
            'title'=>'required|max:160',
            'description'=>'nullable|max:1000',
            'start_at'=>'required|date',
            'end_at'=>'nullable|date|after_or_equal:start_at',
            'location'=>'nullable|max:255'
        ]);
        $calendario->update($data);
        return back()->with('ok','Evento actualizado');
    }

    public function destroy(Event $calendario)
    {
        $calendario->delete();
        return redirect()->route('calendario.index')->with('ok','Evento eliminado');
    }
}