<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\VideoEmbed;

class LibraryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'description',
        'cover_path',
        'file_path',
        'video_url',
        'video_caption',
        'external_url'
    ];

    public function author()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function setVideoUrlAttribute($value): void
    {
        $this->attributes['video_url'] = $value ? trim($value) : null;
    }

    public function getVideoEmbedUrlAttribute(): ?string
    {
        return VideoEmbed::toEmbedUrl($this->video_url);
    }
}