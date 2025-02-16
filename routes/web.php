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

use App\Livewire\CreateModele;

Route::middleware(['auth'])->group(function () {
    Route::get('/modele/create', CreateModele::class)->name('modele.create');
});


Route::get('/generate-download-patron/{id}', [CreateModele::class, 'generateAndDownloadPatron'])
    ->name('generate.download.patron');

Route::get('/modele/{id}', App\Livewire\ShowModele::class)->name('modele.show');
use App\Http\Controllers\PanierController;

Route::post('/panier/ajouter/{id}', [PanierController::class, 'ajouter'])
    ->name('ajouter.au.panier');


require __DIR__.'/auth.php';
