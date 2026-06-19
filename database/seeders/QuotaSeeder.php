<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quota;

class QuotaSeeder extends Seeder
{
    public function run(): void
    {
        $quotas = [
            ['role' => 'Étudiant',      'max_jours' => 14, 'max_livres' => 3],
            ['role' => 'Prof',          'max_jours' => 21, 'max_livres' => 10],
            ['role' => 'Fonctionnaire', 'max_jours' => 7,  'max_livres' => 5],
            ['role' => 'Externe',       'max_jours' => 7,  'max_livres' => 1],
        ];

        foreach ($quotas as $q) {
            // updateOrCreate : idempotent (et met à jour les valeurs si elles changent)
            Quota::updateOrCreate(['role' => $q['role']], $q);
        }
    }
}
