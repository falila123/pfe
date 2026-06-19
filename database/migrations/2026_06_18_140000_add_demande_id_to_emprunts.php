<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emprunts', function (Blueprint $table) {
            if (! Schema::hasColumn('emprunts', 'demande_id')) {
                // null = emprunt effectué sur place (sans demande en ligne)
                $table->foreignId('demande_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('demandes')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('emprunts', function (Blueprint $table) {
            if (Schema::hasColumn('emprunts', 'demande_id')) {
                $table->dropForeign(['demande_id']);
                $table->dropColumn('demande_id');
            }
        });
    }
};
