<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LivreController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\StatistiqueController as AdminStatistiqueController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpruntController;
use App\Http\Controllers\Etudiant\CatalogueController;
use App\Http\Controllers\Etudiant\DemandeController;
use App\Http\Controllers\Bibliothecaire\DemandeController as BiblioDemandeController;
use App\Http\Controllers\Etudiant\EmpruntController as EtudiantEmpruntController;
use App\Http\Controllers\Etudiant\DashboardController as EtudiantDashboardController;
use App\Http\Controllers\Etudiant\NotificationController as EtudiantNotificationController;
use App\Http\Controllers\Bibliothecaire\DashboardController as BiblioDashboardController;
use App\Http\Controllers\Bibliothecaire\StatistiqueController as BiblioStatistiqueController;
use App\Http\Controllers\Administration\DashboardController as AdministrationDashboardController;
use App\Http\Controllers\Administration\StatistiqueController as AdministrationStatistiqueController;


Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD PRINCIPAL
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| ROUTES PROTÉGÉES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | CHANGEMENT DE MOT DE PASSE FORCÉ (1ʳᵉ connexion)
    |--------------------------------------------------------------------------
    */
    Route::get('/changer-mot-de-passe', [\App\Http\Controllers\Auth\PasswordChangeController::class, 'show'])
        ->name('password.change');

    Route::put('/changer-mot-de-passe', [\App\Http\Controllers\Auth\PasswordChangeController::class, 'update'])
        ->name('password.change.update');

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | DASHBOARDS PAR RÔLE
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('role:Administrateur')
        ->name('admin.dashboard');

    Route::get('/bibliothecaire/dashboard', [BiblioDashboardController::class, 'index'])
        ->middleware('role:Bibliothécaire')
        ->name('bibliothecaire.dashboard');

    Route::get('/etudiant/dashboard', [EtudiantDashboardController::class, 'index'])
        ->middleware('role:Étudiant,Prof,Fonctionnaire,Externe')
        ->name('etudiant.dashboard');

    Route::get('/administration/dashboard', [AdministrationDashboardController::class, 'index'])
        ->middleware('role:Administration')
        ->name('administration.dashboard');

    Route::get('/administration/statistiques', [AdministrationStatistiqueController::class, 'index'])
        ->middleware('role:Administration')
        ->name('administration.statistiques.index');

/*
|--------------------------------------------------------------------------
| ÉTUDIANT
|--------------------------------------------------------------------------
*/
Route::prefix('etudiant')
    ->middleware('role:Étudiant,Prof,Fonctionnaire,Externe')
    ->name('etudiant.')
    ->group(function () {

        Route::get('/catalogue', [CatalogueController::class, 'index'])
            ->name('catalogue');

        Route::get('/mes-demandes', [DemandeController::class, 'index'])
            ->name('demandes.index');

        Route::get('/mes-emprunts', [EtudiantEmpruntController::class, 'index'])
            ->name('emprunts.index');

        Route::get('/notifications', [EtudiantNotificationController::class, 'index'])
            ->name('notifications.index');

        Route::post('/demandes', [DemandeController::class, 'store'])
            ->name('demandes.store');

        Route::delete('/demandes/{demande}', [DemandeController::class, 'destroy'])
            ->name('demandes.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN - USERS
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->middleware('role:Administrateur')->group(function () {

        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('admin.users.index');

        Route::post('/users', [AdminUserController::class, 'store'])
            ->name('admin.users.store');

        Route::put('/users/{user}', [AdminUserController::class, 'update'])
            ->name('admin.users.update');

        Route::patch('/users/{user}/toggle', [AdminUserController::class, 'toggleStatus'])
            ->name('admin.users.toggle');

        Route::get('/statistiques', [AdminStatistiqueController::class, 'index'])
            ->name('admin.statistiques.index');
    });

    /*
    |--------------------------------------------------------------------------
    | BIBLIOTHÉCAIRE - LIVRES
    |--------------------------------------------------------------------------
    */
    Route::prefix('bibliothecaire')
        ->middleware('role:Bibliothécaire,Administrateur')
        ->name('bibliothecaire.')
        ->group(function () {

            Route::get('/livres', [LivreController::class, 'index'])
                ->name('livres.index');
                Route::get('/livres/lookup', [LivreController::class, 'lookup'])
                 ->name('livres.lookup');
                Route::get('/demandes', [BiblioDemandeController::class, 'index'])
                 ->name('demandes.index');
                Route::patch('/demandes/{demande}/accepter', [BiblioDemandeController::class, 'accepter'])
                 ->name('demandes.accepter');
                Route::patch('/demandes/{demande}/refuser', [BiblioDemandeController::class, 'refuser'])
                 ->name('demandes.refuser');
                Route::patch('/demandes/{demande}/remettre', [BiblioDemandeController::class, 'remettre'])
                 ->name('demandes.remettre');
                Route::post('/auteurs', [LivreController::class, 'storeAuteur'])
                 ->name('bibliothecaire.auteurs.store');
                 Route::post('/livres/{livre}/exemplaires', [LivreController::class, 'addExemplaire'])
                ->name('livres.exemplaires.store');
                Route::get('/emprunts/suivi', [EmpruntController::class, 'suivi'])
                 ->name('emprunts.suivi');
                 Route::post('/emprunts', [EmpruntController::class, 'store'])
                ->name('emprunts.store');
                Route::patch('/emprunts/{exemplaire}/retour', [EmpruntController::class, 'retour'])
                    ->name('emprunts.retour');
                Route::get('/statistiques', [BiblioStatistiqueController::class, 'index'])
                 ->name('statistiques.index');

        });

    /*
    |--------------------------------------------------------------------------
    | LIVRES (CREATE/STORE)
    |--------------------------------------------------------------------------
    */
    Route::get('/livres/create', [LivreController::class, 'create'])
        ->middleware('role:Bibliothécaire,Administrateur')
        ->name('livres.create');

    Route::post('/livres', [LivreController::class, 'store'])
        ->middleware('role:Bibliothécaire,Administrateur')
        ->name('livres.store');
    Route::put('/livres/{livre}', [LivreController::class, 'update'])
    ->middleware('role:Bibliothécaire,Administrateur')
    ->name('livres.update');
    Route::delete('/exemplaires/{id}', [LivreController::class, 'destroyExemplaire'])
    ->middleware('role:Bibliothécaire,Administrateur')
    ->name('exemplaires.destroy');

});

require __DIR__.'/auth.php';