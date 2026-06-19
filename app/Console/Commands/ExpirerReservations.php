<?php

namespace App\Console\Commands;

use App\Models\Demande;
use Illuminate\Console\Command;

class ExpirerReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demandes:expirer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire les réservations 24h non récupérées et relibère les exemplaires';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = Demande::expirerReservationsDepassees();

        $this->info("{$count} réservation(s) expirée(s).");

        return self::SUCCESS;
    }
}
