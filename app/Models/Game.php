<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\GameAttempt;

class Game extends Model {
    use HasFactory;

    public const TYPES = [
        'quiz',
        'memoria',
    ];

    public const TYPE_LABELS = [
        'quiz' => 'Quiz interactivo',
        'memoria' => 'Memoria de parejas',
    ];

    public const TYPE_DESCRIPTIONS = [
        'quiz' => 'Preguntas de opción múltiple con calificación inmediata.',
        'memoria' => 'Encuentra la pareja correcta entre tarjetas de pregunta y respuesta.',
    ];

    protected $fillable=['user_id','title','description','points_per_question','type'];

    public static function typeLabel(string $type): string
    {
        return self::TYPE_LABELS[$type] ?? ucfirst($type);
    }

    public static function typeDescription(string $type): ?string
    {
        return self::TYPE_DESCRIPTIONS[$type] ?? null;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::typeLabel($this->type ?? 'quiz');
    }

    public function author(){ return $this->belongsTo(User::class,'user_id'); }

    public function questions(){ return $this->hasMany(Question::class); }

    public function attempts()
    {
        return $this->hasMany(GameAttempt::class);
    }
}
