@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Nueva Historia</h1>

<form method="POST" action="{{ route('historia.store') }}" enctype="multipart/form-data" class="space-y-4">
  @csrf
  <input type="text" name="title" placeholder="Título" class="w-full border p-2" required>
  <textarea name="excerpt" placeholder="Extracto" class="w-full border p-2"></textarea>
  <textarea name="content" placeholder="Contenido" class="w-full border p-2 h-40"></textarea>
  <input type="file" name="cover">
  <button class="px-4 py-2 bg-green-600 text-white">Guardar</button>
</form>
@endsection