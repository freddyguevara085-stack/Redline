<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\VideoEmbed;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class History extends Model {
    use HasFactory;
    protected $fillable = ['user_id','category_id','title','slug','excerpt','content','cover_path','video_url','era','leading_figure','published_at'];
    protected $casts = ['published_at'=>'datetime'];

    public function author(){ return $this->belongsTo(User::class,'user_id'); }
    public function category(){ return $this->belongsTo(Category::class); }
    public function comments(){ return $this->morphMany(Comment::class,'commentable')->latest(); }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::saved(function () {
            cache()->forget('histories:eras');
            cache()->forget('histories:leading_figures');
        });
        static::deleted(function () {
            cache()->forget('histories:eras');
            cache()->forget('histories:leading_figures');
        });
    }

    public function setVideoUrlAttribute($value): void
    {
        $this->attributes['video_url'] = $value ? trim($value) : null;
    }

    public function getVideoEmbedUrlAttribute(): ?string
    {
        return VideoEmbed::toEmbedUrl($this->video_url);
    }

    public function getCoverUrlAttribute(): ?string
    {
        if (! $this->cover_path) {
            return null;
        }

        if (! Storage::disk('public')->exists($this->cover_path)) {
            return null;
        }

        if (Route::has('historia.cover')) {
            return route('historia.cover', $this);
        }

        return Storage::disk('public')->url($this->cover_path);
    }
    }
