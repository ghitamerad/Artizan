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

    private function getMesuresFromCache($modeleId)
{
    $userId = Auth::id();
    return Cache::get('mesures_user_' . $userId . '_modele_' . $modeleId, []);
}


public function render()
{
    $panierAvecMesures = [];

    foreach ($this->panier as $item) {
        if ($item['custom']) {
            $mesuresBrutes = $this->getMesuresFromCache($item['id']);
            $mesuresNomValeur = [];

            // Récupérer les mesures du modèle
            $mesures = \App\Models\Mesure::where('modele_id', $item['id'])->get();

            // Associer nom => valeur
            foreach ($mesures as $mesure) {
                $valeur = $mesuresBrutes[$mesure->id] ?? $mesure->valeur_par_defaut;
                $mesuresNomValeur[$mesure->nom] = $valeur;
            }

            $item['mesures'] = $mesuresNomValeur;
        }
        $panierAvecMesures[] = $item;
    }

    return view('livewire.panier', [
        'panier' => $panierAvecMesures,
        'totalArticles' => $this->totalArticles,
    ])->layout('layouts.test');
}


}
