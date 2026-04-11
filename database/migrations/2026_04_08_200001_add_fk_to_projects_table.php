<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Link to jenis_proyek (replaces free-text 'category')
            $table->foreignId('jenis_proyek_id')->nullable()->after('year')
                  ->constrained('jenis_proyek')->onDelete('set null');
            // Link to clients table (alongside free-text 'client')
            $table->foreignId('client_id')->nullable()->after('client')
                  ->constrained('clients')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['jenis_proyek_id']);
            $table->dropForeign(['client_id']);
            $table->dropColumn(['jenis_proyek_id', 'client_id']);
        });
    }
};
