<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\modele;
use App\Models\Panier;

class PanierComponent extends Component
{
    public function ajouterAuPanier($modeleId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $modele = Modele::findOrFail($modeleId);

        $panier = Panier::firstOrCreate(
            ['user_id' => Auth::id(), 'modele_id' => $modele->id],
            ['quantite' => 1]
        );

        if (!$panier->wasRecentlyCreated) {
            $panier->increment('quantite');
        }

        session()->flash('message', 'Modèle ajouté au panier !');
        $this->emit('panierMisAJour'); // Pour actualiser le panier si besoin
    }

    public function render()
    {
        return view('livewire.panier-component', [
            'paniers' => Panier::where('user_id', Auth::id())->get()
        ])->layout('layout.guest2');
    }
}

