<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->string('type')->default('image')->after('id');
            $table->string('bg_color_start')->nullable()->after('type');
            $table->string('bg_color_end')->nullable()->after('bg_color_start');
            $table->string('text_align')->default('center')->after('bg_color_end');
        });

        // Make image nullable via raw statement (compatible with older doctrine/dbal)
        Schema::table('sliders', function (Blueprint $table) {
            $table->string('image')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn(['type', 'bg_color_start', 'bg_color_end', 'text_align']);
        });
        Schema::table('sliders', function (Blueprint $table) {
            $table->string('image')->nullable(false)->change();
        });
    }
};
