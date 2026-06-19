<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Téléphone (obligatoire pour les externes, optionnel sinon)
            if (! Schema::hasColumn('users', 'telephone')) {
                $table->string('telephone')->nullable()->after('matricule');
            }

            // N° de pièce d'identité (CIN / passeport) — pour les externes
            if (! Schema::hasColumn('users', 'numero_piece')) {
                $table->string('numero_piece')->nullable()->after('telephone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'telephone')) {
                $table->dropColumn('telephone');
            }
            if (Schema::hasColumn('users', 'numero_piece')) {
                $table->dropColumn('numero_piece');
            }
        });
    }
};
