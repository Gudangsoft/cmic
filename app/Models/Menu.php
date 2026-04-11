<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Menu extends Model
{
    protected $fillable = ['label', 'url', 'route_name', 'icon', 'parent_id', 'order', 'target', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    public function link(): string
    {
        if ($this->route_name && Route::has($this->route_name)) {
            return route($this->route_name);
        }
        return $this->url ?: '#';
    }

    public function isActive(): bool
    {
        if ($this->route_name && Route::has($this->route_name)) {
            return request()->routeIs($this->route_name);
        }
        if ($this->url && $this->url !== '#') {
            return request()->is(ltrim($this->url, '/'));
        }
        return false;
    }
}
