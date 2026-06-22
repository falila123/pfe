<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emprunts', function (Blueprint $table) {
            if (! Schema::hasColumn('emprunts', 'rappel_envoye')) {
                $table->boolean('rappel_envoye')->default(false)->after('statut');
            }
        });
    }

    public function down(): void
    {
        Schema::table('emprunts', function (Blueprint $table) {
            if (Schema::hasColumn('emprunts', 'rappel_envoye')) {
                $table->dropColumn('rappel_envoye');
            }
        });
    }
};
