<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\modele;
use App\Models\Panier;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Auth;


class ShowModele extends Component
{
    public modele $modele;

    public function ajouterAuPanier($id)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $userId = Auth::id();
    $panier = Cache::get("panier_{$userId}", []);

    $modele = Modele::findOrFail($id);

    if (isset($panier[$id])) {
        $panier[$id]['quantite']++;
    } else {
        $panier[$id] = [
            'id' => $modele->id,
            'nom' => $modele->nom,
            'prix' => $modele->prix,
            'quantite' => 1,
        ];
    }

    Cache::put("panier_{$userId}", $panier, now()->addHours(2));

    session()->flash('message', 'Modèle ajouté au panier !');
}

    public function mount($id)
    {
        $this->modele = modele::with('categorie')->findOrFail($id);
    }

    public function commanderSurMesure($modeleId)
{
    return redirect()->route('modeles.mesures', ['modele' => $modeleId]);
}

    // public function ajouterAuPanier($modeleId, $quantite)
    // {
    //     $user = Auth::user();
    //     if (!$user) {
    //         session()->flash('error', 'Vous devez être connecté pour ajouter au panier.');
    //         return;
    //     }

    //     // Ajout au panier (exemple)
    //     Panier::create([
    //         'user_id' => $user->id,
    //         'modele_id' => $modeleId,
    //         'quantite' => $quantite,
    //     ]);

    //     session()->flash('success', 'Article ajouté au panier.');
    //     $this->dispatch('panierMisAJour'); // Pour actualiser le panier dans d'autres composants si besoin
    // }

    public function render()
    {
        return view('livewire.show-modele')->layout('layouts.test');
    }
}
