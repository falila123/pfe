<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Utilisateur connecté devant changer son mot de passe
        if ($user && $user->must_change_password) {

            // Routes autorisées sans blocage (la page de changement + déconnexion)
            $autorisees = [
                'password.change',
                'password.change.update',
                'logout',
            ];

            if (! $request->routeIs($autorisees)) {
                return redirect()->route('password.change');
            }
        }

        return $next($request);
    }
}
