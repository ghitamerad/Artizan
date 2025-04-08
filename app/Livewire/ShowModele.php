<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Modele;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class ShowModele extends Component
{
    public Modele $modele;

    public function ajouterAuPanier($id)
    {
        $modele = Modele::findOrFail($id);

        if (Auth::check()) {
            $userId = Auth::id();
            $panier = Cache::get("panier_{$userId}", []);
        } else {
            $panier = Session::get('panier_invite', []);
        }

        if (isset($panier[$id])) {
            $panier[$id]['quantite']++;
        } else {
            $panier[$id] = [
                'id' => $modele->id,
                'nom' => $modele->nom,
                'prix' => $modele->prix,
                'quantite' => 1,
                'custom' => false, // Mode classique
            ];
        }

        if (Auth::check()) {
            Cache::put("panier_{$userId}", $panier, now()->addHours(2));
        } else {
            Session::put('panier_invite', $panier);
        }

        $totalArticles = array_sum(array_column($panier, 'quantite'));
        $this->dispatch('panierMisAJour', total: $totalArticles);


        session()->flash('message', '✅ Modèle ajouté au panier avec succès !');

        // Ajouter un message Livewire pour une notification front-end
        $this->dispatch('ajoutReussi', message: 'Modèle ajouté au panier !');    }

    public function commanderSurMesure($id)
    {
        $modele = Modele::findOrFail($id);

        if (Auth::check()) {
            $userId = Auth::id();
            $panier = Cache::get("panier_{$userId}", []);
        } else {
            $panier = Session::get('panier_invite', []);
        }

        if (isset($panier[$id])) {
            $panier[$id]['quantite']++;
        } else {
            $panier[$id] = [
                'id' => $modele->id,
                'nom' => $modele->nom,
                'prix' => $modele->prix,
                'quantite' => 1,
                'custom' => true, // Commande sur mesure
            ];
        }

        if (Auth::check()) {
            Cache::put("panier_{$userId}", $panier, now()->addHours(2));
        } else {
            Session::put('panier_invite', $panier);
        }

        session()->flash('message', 'Commande sur mesure ajoutée au panier !');

        // On autorise la redirection vers les mesures même sans être connecté
        return redirect()->route('modeles.mesures', ['modele' => $id]);
    }

    public function mount($id)
    {
        $this->modele = Modele::with('categorie')->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.show-modele')->layout('layouts.test');
    }
}
