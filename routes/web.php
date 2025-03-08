<?php

use Illuminate\Support\Facades\Route;


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


use App\Http\Controllers\UserController;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::post('/admin/users/store', [UserController::class, 'store'])->name('admin.users.store');


Route::get('/', App\Livewire\Home::class)->name('home');

use App\http\Controllers\ModeleController;

Route::middleware(['auth', 'can:viewAny,App\Models\Modele'])->group(function () {
    Route::get('/modeles/create', [ModeleController::class, 'create'])->name('modeles.create');
    Route::post('/modeles/store', [ModeleController::class, 'store'])->name('modeles.store');
    Route::get('/modeles/{modele}/edit', [ModeleController::class, 'edit'])->name('modeles.edit');
    Route::put('/modeles/{modele}/update', [ModeleController::class, 'update'])->name('modeles.update');
    Route::delete('/modeles/{modele}/destroy', [ModeleController::class, 'destroy'])->name('modeles.destroy');
});

use App\Http\Controllers\CommandeController;
use App\Http\Controllers\DetailCommandeController;
use App\Http\Controllers\MesureController;

Route::middleware('auth')->group(function () {
    // Afficher toutes les commandes (admin & gérante)
    Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');

    // Formulaire de création d'une commande
    Route::get('/commandes/create', function () {
        return view('commandes.create');
    })->name('commandes.create');
    // Enregistrer une nouvelle commande
    Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');


    // Afficher une commande spécifique (admin, gérante ou propriétaire)
    Route::get('/commandes/{commande}', [CommandeController::class, 'show'])->name('commandes.show');

    // Modifier une commande (uniquement admin & gérante)
    Route::get('/commandes/{commande}/edit', [CommandeController::class, 'edit'])->name('commandes.edit');
    Route::put('/commandes/{commande}', [CommandeController::class, 'update'])->name('commandes.update');

    // Supprimer une commande (admin & gérante)
    Route::delete('/commandes/{commande}', [CommandeController::class, 'destroy'])->name('commandes.destroy');

    // Validation et invalidation des commandes (admin & gérante)
    Route::post('/commandes/{commande}/valider', [CommandeController::class, 'validateCommande'])->name('commandes.validate');
    Route::post('/commandes/{commande}/invalider', [CommandeController::class, 'unvalidateCommande'])->name('commandes.invalidate');
});

Route::get('/commandes/{detail}/detail_commande', [DetailCommandeController::class, 'detailCommande'])
    ->name('commandes.detail_commande');

Route::post('/commandes/{detail}/assigner', [DetailCommandeController::class, 'assignerCouturiere'])
    ->name('commandes.assigner_couturiere');


Route::get('/detail_commandes/{detail_commande}', [DetailCommandeController::class, 'show'])->name('commandes.details');


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

use App\Http\Controllers\PanierController;

Route::post('/panier/ajouter/{id}', [PanierController::class, 'ajouter'])
    ->name('ajouter.au.panier');

use App\Livewire\PanierComponent;

Route::get('/panier', PanierComponent::class)->middleware('auth')->name('panier');



Route::get('/mesures/{mesure}/edit', [MesureController::class, 'edit'])->name('mesures.edit');
Route::put('/mesures/{mesure}', [MesureController::class, 'update'])->name('mesures.update');
Route::post('/mesures', [MesureController::class, 'store'])->name('mesures.store');
Route::delete('/mesures/{mesure}', [MesureController::class, 'destroy'])->name('mesures.destroy');

Route::get('/modeles/{modele}/mesures', [MesureController::class, 'showMesuresForm'])->name('modeles.mesures');



Route::post('/mesures/extract/{modele}', [MesureController::class, 'importMesuresFromVit'])->name('mesures.extract');


use App\Http\Controllers\PatronController;

Route::post('/modeles/{modele}/generate-patron', [PatronController::class, 'generatePatron'])->name('patron.generate');

Route::get('/patron/{modele}', [PatronController::class, 'showPatron'])->name('patron.show');

//
require __DIR__ . '/auth.php';
