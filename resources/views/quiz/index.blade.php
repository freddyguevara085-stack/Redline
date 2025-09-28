@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Quiz de Historia</h1>
    <form action="{{ route('quiz.submit') }}" method="POST">
        @csrf
        @foreach($questions as $i => $question)
            <div class="mb-4">
                <p><strong>{{ $i+1 }}. {{ $question->question }}</strong></p>
                @foreach($question->options as $option)
                    <div>
                        <input type="radio" name="answers[{{ $i }}][selected_answer]" value="{{ $option }}" required>
                        <label>{{ $option }}</label>
                    </div>
                @endforeach
                <input type="hidden" name="answers[{{ $i }}][question_id]" value="{{ $question->id }}">
            </div>
        @endforeach
        <button type="submit" class="btn btn-primary">Enviar respuestas</button>
    </form>
</div>
@endsection
