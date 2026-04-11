<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'type', 'title', 'subtitle',
        'bg_color_start', 'bg_color_end', 'text_align',
        'image', 'button_text', 'button_link', 'order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function isText(): bool
    {
        return $this->type === 'text';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
