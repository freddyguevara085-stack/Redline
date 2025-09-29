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

    protected $casts = [
        'points' => 'integer',
        'score' => 'integer',
        'quizzes_taken' => 'integer',
        'games_played' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getAverageScoreAttribute(): float
    {
        $games = $this->games_played ?? 0;
        if ($games <= 0) {
            return 0.0;
        }

        return round(($this->score ?? 0) / $games, 1);
    }
}