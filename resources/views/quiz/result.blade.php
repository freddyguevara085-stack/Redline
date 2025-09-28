@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Resultado del Quiz</h1>
    <div class="alert alert-info">
        Tu puntaje: <strong>{{ $score }}</strong>
    </div>
    <a href="{{ route('quiz.index') }}" class="btn btn-success">Intentar otro quiz</a>
</div>
@endsection
