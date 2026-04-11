<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'banner_image', 'meta_title', 'meta_description', 'is_active', 'order'];

    protected $casts = ['is_active' => 'boolean'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = static::generateUniqueSlug($page->title);
            }
        });
    }

    public static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }
        return $slug;
    }

    public function getUrlAttribute(): string
    {
        return route('page.show', $this->slug);
    }
}
