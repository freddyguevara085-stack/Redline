<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model {
    use HasFactory;
    protected $fillable=['user_id','body','vote'];
    public function commentable(){ return $this->morphTo(); }
    public function author(){ return $this->belongsTo(User::class,'user_id'); }
}