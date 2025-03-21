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
                'custom' => false, // Mode classique
            ];
        }

        Cache::put("panier_{$userId}", $panier, now()->addHours(2));

        session()->flash('message', 'Modèle ajouté au panier !');
    }

    public function commanderSurMesure($id)
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
                'custom' => true, // Commande sur mesure
            ];
        }

        Cache::put("panier_{$userId}", $panier, now()->addHours(2));

        session()->flash('message', 'Commande sur mesure ajoutée au panier !');
        return redirect()->route('modeles.mesures', ['modele' => $id]);

    }

    public function mount($id)
    {
        $this->modele = modele::with('categorie')->findOrFail($id);
    }



    public function render()
    {
        return view('livewire.show-modele')->layout('layouts.test');
    }
}
