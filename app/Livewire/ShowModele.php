<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\modele;
use Illuminate\Support\Facades\Auth;


class ShowModele extends Component
{
    public modele $modele;

    public function mount($id)
    {
        $this->modele = modele::with('categorie')->findOrFail($id);
    }

    public function ajouterAuPanier($modeleId, $quantite)
    {
        $user = Auth::user();
        if (!$user) {
            session()->flash('error', 'Vous devez être connecté pour ajouter au panier.');
            return;
        }

        // Ajout au panier (exemple)
        Panier::create([
            'user_id' => $user->id,
            'modele_id' => $modeleId,
            'quantite' => $quantite,
        ]);

        session()->flash('success', 'Article ajouté au panier.');
        $this->emit('panierMisAJour'); // Pour actualiser le panier dans d'autres composants si besoin
    }

    public function render()
    {
        return view('livewire.show-modele')->layout('layouts.guest2');
    }
}
