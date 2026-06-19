<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Comptes par défaut (idempotent : ne crée que s'ils n'existent pas)

        User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('admin123'),
                'role'     => 'Administrateur',
                'status'   => 'actif',
            ]
        );

        User::firstOrCreate(
            ['email' => 'biblio@mail.com'],
            [
                'name'     => 'Bibliothécaire',
                'password' => Hash::make('biblio123'),
                'role'     => 'Bibliothécaire',
                'status'   => 'actif',
            ]
        );

        User::firstOrCreate(
            ['email' => 'etudiant@mail.com'],
            [
                'name'      => 'Étudiant Test',
                'password'  => Hash::make('etudiant123'),
                'role'      => 'Étudiant',
                'matricule' => '22A001FS',
                'status'    => 'actif',
            ]
        );

        User::firstOrCreate(
            ['email' => 'administration@mail.com'],
            [
                'name'     => 'Administration',
                'password' => Hash::make('admin123'),
                'role'     => 'Administration',
                'status'   => 'actif',
            ]
        );
    }
}
