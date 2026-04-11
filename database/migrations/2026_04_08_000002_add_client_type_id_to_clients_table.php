<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->foreignId('client_type_id')->nullable()->constrained('client_types')->nullOnDelete()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\ClientType::class);
            $table->dropColumn('client_type_id');
        });
    }
};
