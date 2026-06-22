<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 🕒 Expiration automatique des réservations 24h (toutes les heures)
Schedule::command('demandes:expirer')->hourly();

// 📧 Rappel automatique J-1 : livres à rendre demain (chaque jour à 8h)
Schedule::command('emprunts:rappels')->dailyAt('08:00');
