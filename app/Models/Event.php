<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model {
    use HasFactory;
    protected $fillable=['user_id','title','description','start_at','end_at','location'];
    protected $casts=['start_at'=>'datetime','end_at'=>'datetime'];
    public function author(){ return $this->belongsTo(User::class,'user_id'); }
}