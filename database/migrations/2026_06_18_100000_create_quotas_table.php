<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotas', function (Blueprint $table) {
            $table->id();
            $table->string('role')->unique();          // Étudiant, Prof, Fonctionnaire, Externe
            $table->unsignedInteger('max_jours');       // durée max d'emprunt (jours)
            $table->unsignedInteger('max_livres');      // nombre max de livres simultanés
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotas');
    }
};
