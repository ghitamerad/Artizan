<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class PanierBouton extends Component
{
    public $panier = [];
    public $totalArticles = 0;

    protected $listeners = ['panierMisAJour' => 'mettreAJourPanier'];

    public function mount()
    {
        $this->mettreAJourPanier();
    }

    public function mettreAJourPanier()
    {
        if (Auth::check()) {
            $this->panier = Cache::get("panier_" . Auth::id(), []);
        } else {
            $this->panier = Session::get('panier_invite', []);
        }

        $this->totalArticles = array_sum(array_column($this->panier, 'quantite'));
    }

    public function render()
    {
        return view('livewire.panier-bouton');
    }
}

