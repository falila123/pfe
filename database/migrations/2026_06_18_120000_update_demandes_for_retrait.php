<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // demandes : statut en string + exemplaire réservé + délai de retrait
        Schema::table('demandes', function (Blueprint $table) {

            // enum -> string (pour autoriser Récupérée / Expirée)
            $table->string('statut')->default('En attente')->change();

            if (! Schema::hasColumn('demandes', 'exemplaire_id')) {
                $table->foreignId('exemplaire_id')
                    ->nullable()
                    ->after('livre_id')
                    ->constrained('exemplaires')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('demandes', 'date_limite_retrait')) {
                $table->timestamp('date_limite_retrait')->nullable()->after('motif_refus');
            }
        });

        // exemplaires : statut en string (pour autoriser "Réservé")
        Schema::table('exemplaires', function (Blueprint $table) {
            $table->string('statut')->default('Disponible')->change();
        });
    }

    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            if (Schema::hasColumn('demandes', 'exemplaire_id')) {
                $table->dropForeign(['exemplaire_id']);
                $table->dropColumn('exemplaire_id');
            }
            if (Schema::hasColumn('demandes', 'date_limite_retrait')) {
                $table->dropColumn('date_limite_retrait');
            }
        });
    }
};
