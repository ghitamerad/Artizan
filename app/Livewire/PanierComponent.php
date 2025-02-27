<?php

namespace App\Livewire;

use App\Models\Panier;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PanierComponent extends Component
{
    public $panier = [];
    public $showPanier = false;
    public $totalArticles = 0;

    protected $listeners = ['panierMisAJour' => 'chargerPanier'];

    public function mount()
    {
        $this->chargerPanier();
    }

    public function chargerPanier()
    {
        if (Auth::check()) {
            $this->panier = Panier::where('user_id', Auth::id())->with('modele')->get();
            $this->totalArticles = collect($this->panier)->sum('quantite');
        }
    }

    public function retirerDuPanier($id)
    {
        Panier::where('id', $id)->delete();
        $this->dispatch('panierMisAJour');
    }

    public function togglePanier()
    {
        $this->showPanier = !$this->showPanier;
    }

    public function render()
    {
        return view('livewire.panier', [
            'totalArticles' => $this->totalArticles,
            'showPanier' => $this->showPanier
        ])->layout('layouts.test');    }
}
