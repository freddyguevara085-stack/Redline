<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Game;

class GameAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'user_id',
        'score',
        'correct_answers',
        'total_questions',
        'played_at',
    ];

    protected $casts = [
        'played_at' => 'datetime',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
