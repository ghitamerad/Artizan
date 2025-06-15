<?php

use App\Http\Controllers\AttributController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModeleController; // J'ai déplacé l'importation pour la propreté
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\DetailCommandeController;
use App\Http\Controllers\MesureController;
use Illuminate\Support\Facades\Auth; // J'ai déplacé l'importation
use App\Http\Controllers\PanierController;
use App\Livewire\PanierComponent;
use App\Http\Controllers\PatronController;
use App\Livewire\PretAPorter;
use App\Livewire\SurMesure;
use App\Livewire\CouturiereDashboard;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\AttributValeurController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\ElementPatronController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route pour la page d'accueil principale (Livewire)
Route::get('/', App\Livewire\Home::class)->name('home');


// Route pour la nouvelle Landing Page
Route::get('/landing', function () {

    return view('landing-page');
})->name('landing-page');

Route::get('/prod', function () {

    return view('prod');
})->name('prod');

Route::get('/graph', function () {

    return view('statistiques.graph');
})->name('graph');


Route::get('/questionnaire', function () {

    return view('questionnaire');
})->name('questionnaire');

// Dashboard et Profil (authentification requise)
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Routes Admin pour les Utilisateurs
Route::middleware(['auth']) // Assurez-vous d'avoir un middleware 'admin' ou adaptez
    ->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store'); // Utilisé pour la création
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Routes pour les Modèles (CRUD par admin/gérante)
Route::middleware(['auth', 'can:viewAny,App\Models\Modele'])->group(function () { // Adaptez le middleware si besoin
    Route::get('/modeles', [ModeleController::class, 'index'])->name('modeles.index'); // Liste des modèles
    Route::get('/modeles/create', [ModeleController::class, 'create'])->name('modeles.create');
    Route::get('/modeles/{modele}/show', [ModeleController::class, 'show'])->name('modeles.show'); // Affichage d'un modèle spécifique via Livewire
    Route::post('/modeles', [ModeleController::class, 'store'])->name('modeles.store'); // Changé de /modeles/store
    Route::get('/modeles/{modele}/edit', [ModeleController::class, 'edit'])->name('modeles.edit');
    Route::put('/modeles/{modele}', [ModeleController::class, 'update'])->name('modeles.update'); // Changé de /modeles/{modele}/update
    Route::delete('/modeles/{modele}', [ModeleController::class, 'destroy'])->name('modeles.destroy'); // Changé de /modeles/{modele}/destroy
});

// Routes publiques pour voir les modèles
use App\Livewire\ShowModele;

Route::get('/modele/{id}', ShowModele::class)->name('modele.show');


// Routes pour les Commandes (authentification requise)
Route::middleware('auth')->group(function () {
    Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');
    Route::get('/commandes/create', function () { return view('commandes.create'); })->name('commandes.create');
    Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');
    Route::get('/commandes/{commande}', [CommandeController::class, 'show'])->name('commandes.show'); // Note: conflit potentiel avec modele.show si l'ID est numérique. Préfixer ou clarifier.
    Route::get('/commandes/{commande}/edit', [CommandeController::class, 'edit'])->name('commandes.edit');
    Route::put('/commandes/{commande}', [CommandeController::class, 'update'])->name('commandes.update');
    Route::delete('/commandes/{commande}', [CommandeController::class, 'destroy'])->name('commandes.destroy');
    Route::post('/commandes/{commande}/valider', [CommandeController::class, 'validateCommande'])->name('commandes.validate');
    Route::post('/commandes/{commande}/invalider', [CommandeController::class, 'unvalidateCommande'])->name('commandes.invalidate');
});

// Routes pour les Détails de Commande
Route::get('/commandes/detail/{detail_commande}', [DetailCommandeController::class, 'show']) // Changé pour éviter conflit
    ->name('commandes.detail_commande'); // Renommé pour clarté
Route::get('/detail_commande/{detail_commande}/edit', [DetailCommandeController::class, 'edit'])->name('detail_commande.edit');
Route::put('/detail_commande/{detail_commande}', [DetailCommandeController::class, 'update'])->name('detail_commande.update');
Route::post('/commandes/detail/{detail}/assigner', [DetailCommandeController::class, 'assignerCouturiere']) // Changé pour éviter conflit
    ->name('commandes.assigner_couturiere');
Route::get('/detail_commandes/show/{detail_commande}', [DetailCommandeController::class, 'show'])->name('commandes.details'); // Renommé pour clarté

// Routes pour le Panier
Route::post('/panier/ajouter/{id}', [PanierController::class, 'ajouter'])->name('ajouter.au.panier');
Route::get('/panier', PanierComponent::class)->name('panier');

// Routes pour les Mesures
Route::middleware('auth')->group(function () {
    Route::get('/mesures/{mesure}/edit', [MesureController::class, 'edit'])->name('mesures.edit');
    Route::put('/mesures/{mesure}', [MesureController::class, 'update'])->name('mesures.update');
    Route::post('/mesures', [MesureController::class, 'store'])->name('mesures.store');
    Route::delete('/mesures/{mesure}', [MesureController::class, 'destroy'])->name('mesures.destroy');
    Route::get('/modeles/{modele}/mesures', [MesureController::class, 'showMesuresForm'])->name('modeles.mesures');
    Route::post('/mesures/extract/{modele}', [MesureController::class, 'importMesuresFromVit'])->name('mesures.extract');
});

// Routes pour la Génération de Patrons
Route::middleware('auth')->group(function () {
    Route::post('/modeles/{modele}/generate-patron', [PatronController::class, 'generatePatron'])->name('patron.generate');
    Route::get('/patron/{modele}', [PatronController::class, 'showPatron'])->name('patron.show');
    Route::get('/patron/personnalise/{detailCommandeId}', [PatronController::class, 'customPattern'])->name('patron.custom');
    Route::get('/patron/personnalise/afficher/{detailCommandeId}', [PatronController::class, 'showCustomPattern'])->name('patron.custom.show');
    Route::get('/patron/telecharger/{id}', [PatronController::class, 'telecharger'])->name('patron.telecharger');
});

// Routes spécifiques Prêt-à-Porter et Sur-Mesure (Livewire)
Route::get('/pret-a-porter', PretAPorter::class)->name('pret-a-porter');
Route::get('/sur-mesure', SurMesure::class)->name('sur-mesure');

// Routes pour le Dashboard Couturière
Route::get('/couturiere', function () {
    return view('couturiere.index');
})->middleware('auth')->name('couturiere.dashboard'); // Ajout middleware auth

Route::middleware(['auth']) // Assurez-vous d'avoir un middleware 'couturiere' ou adaptez
    ->prefix('couturiere')->name('couturiere.')->group(function () {
    Route::get('/commandes', [DetailCommandeController::class, 'commandesCouturiere'])->name('commandes');
    Route::post('/commandes/{id}/terminer', [DetailCommandeController::class, 'terminerCommande'])->name('commandes.terminer');
});

// Routes pour "Mes Commandes" client
Route::middleware('auth')->group(function () {
    Route::get('/mes-commandes', [DetailCommandeController::class, 'index'])->name('detail-commandes.index');
    Route::get('/mes-commandes/{id}', [DetailCommandeController::class, 'showClient'])->name('detail-commandes.showClient');
});


// Routes pour les Catégories (Admin/Gérante)
Route::middleware(['auth']) // Ajoutez une policy ou un middleware plus spécifique si besoin (ex: 'can:manage-categories')
    ->prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [CategorieController::class, 'index'])->name('index');
    Route::get('/create', [CategorieController::class, 'create'])->name('create');
    Route::post('/', [CategorieController::class, 'store'])->name('store'); // Changé de /store
    Route::get('/{categorie}/edit', [CategorieController::class, 'edit'])->name('edit');
    Route::put('/{categorie}', [CategorieController::class, 'update'])->name('update'); // Changé de /update
    Route::delete('/{categorie}', [CategorieController::class, 'destroy'])->name('destroy'); // Changé de /destroy
});

// Routes pour les Attributs (Admin/Gérante)
Route::middleware(['auth']) // Ajoutez une policy ou un middleware plus spécifique
    ->prefix('attributs')->name('attributs.')->group(function () {
    Route::get('/', [AttributController::class, 'index'])->name('index');
    Route::get('/create', [AttributController::class, 'create'])->name('create');
    Route::post('/', [AttributController::class, 'store'])->name('store');
    Route::get('/{attribut}/edit', [AttributController::class, 'edit'])->name('edit');
    Route::put('/{attribut}', [AttributController::class, 'update'])->name('update');
    Route::delete('/{attribut}', [AttributController::class, 'destroy'])->name('destroy');

});



Route::middleware(['auth'])->group(function () { // Ajoutez une policy ou un middleware
    Route::get('/{attribut}/valeurs/create', [AttributValeurController::class, 'create'])->name('valeurs.create');
Route::post('/valeurs', [AttributValeurController::class, 'store'])->name('valeurs.store');
    Route::get('/valeurs/{valeur}/edit', [AttributValeurController::class, 'edit'])->name('valeurs.edit');
    Route::put('/valeurs/{valeur}', [AttributValeurController::class, 'update'])->name('valeurs.update');
    Route::delete('/valeurs/{valeur}', [AttributValeurController::class, 'destroy'])->name('valeurs.destroy');
});


// Routes pour ElementPatron et Devis (Resource controllers)
Route::middleware('auth')->group(function(){ // Ajoutez une policy ou un middleware
    Route::resource('element-patrons', ElementPatronController::class);
    Route::resource('devis', DevisController::class);
});


// Route de déconnexion
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('devis/{devis}/generer-patron', [DevisController::class, 'formGenererPatron'])
    ->name('devis.formGenererPatron');

Route::post('devis/{devis}/generer-patron', [DevisController::class, 'genererPatron'])
    ->name('devis.genererPatron');

Route::middleware(['auth'])->group(function () {
    Route::get('/demander-devis', [DevisController::class, 'createClient'])->name('devis.demande');
});

Route::post('/patrons/generer/{id}', [ElementPatronController::class, 'genererPatronPersonnalise'])->name('devis.generer');

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');


Route::put('/devis/{devi}/repondre', [DevisController::class, 'repondre'])->name('devis.repondre');

Route::get('/mes-devis', [DevisController::class, 'indexClient'])->name('mes-devis.index');

Route::get('/mes-devis/{devi}', [DevisController::class, 'showClient'])->name('devis.client.show');

Route::post('/devis/{devi}/reponse', [DevisController::class, 'repondreClient'])->name('devis.repondreClient');


require __DIR__ . '/auth.php';
