<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // On récupère tout l'historique (avant de marquer comme lu,
        // pour pouvoir mettre en évidence les nouvelles)
        $notifications = $user->notifications()->latest()->get();

        // ✅ Marquage "lu" à l'ouverture
        $user->unreadNotifications->markAsRead();

        return view('etudiant.notifications.index', compact('notifications'));
    }
}
