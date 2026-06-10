<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LivreController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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

    Route::view('/bibliothecaire/dashboard', 'bibliothecaire.dashboard')
        ->middleware('role:Bibliothécaire')
        ->name('bibliothecaire.dashboard');

    Route::view('/etudiant/dashboard', 'etudiant.dashboard')
        ->middleware('role:Étudiant')
        ->name('etudiant.dashboard');

    Route::view('/administration/dashboard', 'administration.dashboard')
        ->middleware('role:Administration')
        ->name('administration.dashboard');

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
                Route::post('/auteurs', [LivreController::class, 'storeAuteur'])
                 ->name('bibliothecaire.auteurs.store');
                 Route::post('/livres/{livre}/exemplaires', [LivreController::class, 'addExemplaire'])
                ->name('livres.exemplaires.store');

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