<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

    use App\Http\Controllers\UserController;

    Route::middleware(['auth', 'can:viewAny,App\Models\User'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index'); // Affiche tous les utilisateurs
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create'); // Formulaire d'ajout
        Route::post('/users', [UserController::class, 'store'])->name('users.store'); // Enregistre un utilisateur
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit'); // Formulaire d'édition
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update'); // Met à jour un utilisateur
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy'); // Supprime un utilisateur
    });

Route::get('/home', App\Livewire\Home::class)->name('home');

use App\http\Controllers\ModeleController;

Route::middleware(['auth', 'can:viewAny,App\Models\Modele'])->group(function () {
    Route::get('/modeles/create', [ModeleController::class, 'create'])->name('modeles.create');
    Route::post('/modeles/store', [ModeleController::class, 'store'])->name('modeles.store');
    Route::get('/modeles/{modele}/edit', [ModeleController::class, 'edit'])->name('modeles.edit');
    Route::put('/modeles/{modele}/update', [ModeleController::class, 'update'])->name('modeles.update');
    Route::delete('/modeles/{modele}/destroy', [ModeleController::class, 'destroy'])->name('modeles.destroy');
});

use App\http\Controllers\CommandeController;


Route::middleware(['auth'])->group(function () {
    Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');

    Route::middleware(['can:validate,commande'])->group(function () {
        Route::post('/commandes/{id}/valider', [CommandeController::class, 'validateCommande'])->name('commandes.validate');
        Route::post('/commandes/{id}/invalider', [CommandeController::class, 'unvalidateCommande'])->name('commandes.unvalidate');
    });

    Route::middleware(['can:assign,commande'])->post('/commandes/{id}/assign', [CommandeController::class, 'assignToCouturiere'])->name('commandes.assign');

    Route::middleware(['can:confirm,commande'])->post('/commandes/{id}/confirmer', [CommandeController::class, 'confirmCommande'])->name('commandes.confirm');
});
// use App\Livewire\CreateModele;

// Route::middleware(['auth'])->group(function () {
//     Route::get('/modele/create', CreateModele::class)->name('modele.create');
// });



// Routes accessibles à tous les utilisateurs connectés
Route::get('modeles', [ModeleController::class, 'index'])->name('modeles.index');
Route::get('modeles/show/{modele}', [ModeleController::class, 'show'])->name('modeles.show');



Route::get('/modele/{id}', App\Livewire\ShowModele::class)->name('modele.show');

require __DIR__.'/auth.php';
