<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'points',
        'score',
        'quizzes_taken',
        'games_played',
        'source',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}