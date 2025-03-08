<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Modele;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class PanierComponent extends Component
{
    public $panier = [];
    public $totalArticles = 0;

    public function mount()
    {
        $this->chargerPanier();
    }

    private function chargerPanier()
    {
        $userId = Auth::id();
        $this->panier = Cache::get("panier_{$userId}", []);
        $this->totalArticles = array_sum(array_column($this->panier, 'quantite'));
    }

    public function retirerDuPanier($modeleId)
    {
        $userId = Auth::id();
        $panier = Cache::get("panier_{$userId}", []);

        if (isset($panier[$modeleId])) {
            if ($panier[$modeleId]['quantite'] > 1) {
                $panier[$modeleId]['quantite']--;
            } else {
                unset($panier[$modeleId]);
            }
        }

        Cache::put("panier_{$userId}", $panier, now()->addHours(2));
        $this->chargerPanier();
    }

    public function viderPanier()
    {
        $userId = Auth::id();
        Cache::forget("panier_{$userId}");
        $this->chargerPanier();
    }
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

    $this->chargerPanier();
    session()->flash('message', 'Modèle ajouté au panier !');
}


    public function render()
    {
        return view('livewire.panier', [
            'panier' => $this->panier,
            'totalArticles' => $this->totalArticles,
        ])->layout('layouts.test');
    }
}
