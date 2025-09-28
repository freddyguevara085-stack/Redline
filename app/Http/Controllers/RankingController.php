<?php

namespace App\Http\Controllers;

use App\Models\UserScore;

class RankingController extends Controller
{
    public function index()
    {
        // Traer usuarios ordenados por score, top 10
        $ranking = UserScore::with('user')
                    ->orderByDesc('score')
                    ->take(10)
                    ->get();

        return view('ranking.index', compact('ranking'));
    }
}