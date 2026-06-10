
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // ROLE (évite duplication)
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')
                    ->default('Étudiant')
                    ->after('email');
            }

            // MATRICULE
            if (!Schema::hasColumn('users', 'matricule')) {
                $table->string('matricule')
                    ->nullable()
                    ->after('role');
            }

            // STATUS
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['actif', 'inactif'])
                    ->default('actif')
                    ->after('matricule');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }

            if (Schema::hasColumn('users', 'matricule')) {
                $table->dropColumn('matricule');
            }

            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};

