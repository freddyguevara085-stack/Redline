<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\GameAttempt;

class Game extends Model {
    use HasFactory;

    public const TYPES = [
        'trivia',
        'ruleta',
        'juicio',
        'memoria',
    ];

    protected $fillable=['user_id','title','description','points_per_question','type'];

    public function author(){ return $this->belongsTo(User::class,'user_id'); }

    public function questions(){ return $this->hasMany(Question::class); }

    public function attempts()
    {
        return $this->hasMany(GameAttempt::class);
    }
}
