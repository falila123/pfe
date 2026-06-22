<?php

namespace App\Console\Commands;

use App\Models\Emprunt;
use App\Notifications\EmpruntRappel;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class EnvoyerRappelsEmprunts extends Command
{
    protected $signature = 'emprunts:rappels';

    protected $description = "Envoie automatiquement un rappel email aux membres dont un livre est à rendre demain (J-1)";

    public function handle()
    {
        $demain = Carbon::tomorrow();

        // Emprunts en cours à rendre demain, non encore rappelés
        $emprunts = Emprunt::with(['livre', 'user'])
            ->where('statut', 'En cours')
            ->whereDate('date_retour_prevue', $demain)
            ->where('rappel_envoye', false)
            ->get();

        $count = 0;

        foreach ($emprunts as $emprunt) {
            if ($emprunt->user) {
                $emprunt->user->notify(new EmpruntRappel($emprunt));
                $emprunt->update(['rappel_envoye' => true]);
                $count++;
            }
        }

        $this->info("{$count} rappel(s) d'échéance envoyé(s).");

        return self::SUCCESS;
    }
}
