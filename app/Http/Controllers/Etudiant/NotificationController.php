<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user   = auth()->user();
        $statut = $request->query('statut');

        // Historique complet (avant marquage "lu", pour mettre en évidence les nouvelles)
        $all = $user->notifications()->latest()->get();

        // 🎚️ Filtre par statut (la colonne data est en JSON texte → filtre en collection)
        $filtered = $statut
            ? $all->filter(fn ($n) => ($n->data['statut'] ?? null) === $statut)->values()
            : $all;

        // 📄 Pagination manuelle (10 par page)
        $perPage = 10;
        $page    = Paginator::resolveCurrentPage('page');

        $notifications = new LengthAwarePaginator(
            $filtered->forPage($page, $perPage)->values(),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // ✅ Marquage "lu" à l'ouverture
        $user->unreadNotifications->markAsRead();

        return view('etudiant.notifications.index', compact('notifications', 'statut'));
    }
}
