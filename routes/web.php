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
use App\Http\Controllers\MesureController;

Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store')->middleware('auth');

// use App\Livewire\CreateModele;

// Route::middleware(['auth'])->group(function () {
//     Route::get('/modele/create', CreateModele::class)->name('modele.create');
// });


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
    use App\Livewire\Panier;

    Route::get('/panier', Panier::class)->name('panier');



Route::post('/mesures/store', [MesureController::class, 'store'])->name('mesures.store');
Route::post('/mesures/edit', [MesureController::class, 'edit'])->name('mesures.edit');
Route::post('/mesures/destroy', [MesureController::class, 'destroy'])->name('mesures.destroy');


//
require __DIR__.'/auth.php';
