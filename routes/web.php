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


Route::middleware(['auth', 'can:viewAny,App\Models\User'])
->prefix('admin')
->name('admin.')
->group(function () {


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

use App\Http\Controllers\CommandeController;

Route::middleware(['auth', 'can:viewAny,App\Models\commande'])->group(function () {
    // 📌 Afficher toutes les commandes (admin/gérante)
    Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');

    // 📌 Formulaire pour créer une nouvelle commande (client)
    Route::get('/commandes/create', [CommandeController::class, 'create'])->name('commandes.create');

    // 📌 Enregistrer une nouvelle commande (client)
    Route::post('/commandes/store', [CommandeController::class, 'store'])->name('commandes.store');

    // 📌 Afficher une commande spécifique
    Route::get('/commandes/{commande}', [CommandeController::class, 'show'])->name('commandes.show');

    // 📌 Formulaire d'édition d'une commande (admin/gérante)
    Route::get('/commandes/{commande}/edit', [CommandeController::class, 'edit'])->name('commandes.edit');

    // 📌 Mettre à jour une commande (admin/gérante)
    Route::put('/commandes/{commande}', [CommandeController::class, 'update'])->name('commandes.update');

    // 📌 Supprimer une commande (admin/gérante)
    Route::delete('/commandes/{commande}', [CommandeController::class, 'destroy'])->name('commandes.destroy');
});


use Illuminate\Support\Facades\Auth;

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');


// Routes accessibles à tous les utilisateurs connectés
Route::get('modeles', [ModeleController::class, 'index'])->name('modeles.index');
Route::get('modeles/show/{modele}', [ModeleController::class, 'show'])->name('modeles.show');



Route::get('/modele/{id}', App\Livewire\ShowModele::class)->name('modele.show');

use App\Livewire\Panier;

Route::middleware(['auth'])->group(function () {
    Route::get('/panier', App\Livewire\Panier::class)->name('panier');
});

require __DIR__.'/auth.php';
