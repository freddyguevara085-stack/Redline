<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Badge;
use App\Models\UserScore;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Relaciones
    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function libraryItems()
    {
        return $this->hasMany(LibraryItem::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class);
    }

    public function score()
    {
        return $this->hasOne(UserScore::class);
    }

    public function isAdmin(){ return $this->role === 'admin'; }
public function isDocente(){ return $this->role === 'docente'; }
public function isEstudiante(){ return $this->role === 'estudiante'; }
    // Atributos asignables
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    // Campos ocultos en arrays
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casting de atributos
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}