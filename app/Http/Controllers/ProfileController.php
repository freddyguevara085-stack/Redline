<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserScore;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $score = UserScore::where('user_id', $user->id)->first();
        $ranking = UserScore::orderByDesc('score')->pluck('user_id')->toArray();
        $position = array_search($user->id, $ranking) !== false ? array_search($user->id, $ranking) + 1 : null;
        $badges = $user->badges()->get();
        $registered = $user->created_at;
        return view('profile.show', compact('user', 'score', 'position', 'badges', 'registered'));
    }
}
