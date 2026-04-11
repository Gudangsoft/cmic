<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['album', 'title', 'category', 'type', 'image', 'video_url', 'description', 'order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (!$this->video_url) return null;
        // YouTube
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]{11})/', $this->video_url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        // Vimeo
        if (preg_match('/vimeo\.com\/(\d+)/', $this->video_url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }
        return $this->video_url;
    }

    public function getVideoThumbnailAttribute(): ?string
    {
        if (!$this->video_url) return null;
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]{11})/', $this->video_url, $m)) {
            return 'https://img.youtube.com/vi/' . $m[1] . '/hqdefault.jpg';
        }
        return null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
