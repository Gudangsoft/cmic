<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

return new class extends Migration
{
    public function up(): void
    {
        // Seed default theme color values if not set
        $defaults = [
            'theme_color_primary'   => '#0057A8',
            'theme_color_secondary' => '#003A78',
            'theme_color_accent'    => '#F5C518',
        ];
        foreach ($defaults as $key => $value) {
            if (!Setting::where('key', $key)->exists()) {
                Setting::set($key, $value);
            }
        }
    }

    public function down(): void
    {
        Setting::whereIn('key', ['theme_color_primary', 'theme_color_secondary', 'theme_color_accent'])->delete();
    }
};
