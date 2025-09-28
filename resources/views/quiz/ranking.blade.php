@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ranking de Usuarios</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Usuario</th>
                <th>Puntaje</th>
                <th>Quizzes Realizados</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ranking as $i => $userScore)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $userScore->user->name ?? 'Usuario' }}</td>
                    <td>{{ $userScore->score }}</td>
                    <td>{{ $userScore->quizzes_taken }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
