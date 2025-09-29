<?php

namespace App\Http\Controllers;

use App\Models\UserScore;

class RankingController extends Controller
{
    public function index()
    {
        // Traer usuarios ordenados por score, top 10
        $ranking = UserScore::with('user')
                    ->whereNotNull('user_id')
                    ->orderByDesc('score')
                    ->orderByDesc('points')
                    ->take(10)
                    ->get();

        return view('ranking.index', compact('ranking'));
    }
}