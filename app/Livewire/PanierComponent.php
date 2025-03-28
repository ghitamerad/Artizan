<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Modele;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PanierComponent extends Component
{
    public $panier = [];
    public $totalArticles = 0;

    public function mount()
    {
        $this->transfererPanierInvite();
        $this->chargerPanier();
    }

    // Transfert du panier de la session au cache utilisateur après connexion
    private function transfererPanierInvite()
    {
        if (Auth::check() && Session::has('panier_invite')) {
            $userId = Auth::id();
            $panierInvite = Session::get('panier_invite');
            $panierUser = Cache::get("panier_{$userId}", []);

            // Fusionner les deux paniers (ajouter ou cumuler les quantités)
            foreach ($panierInvite as $id => $item) {
                if (isset($panierUser[$id])) {
                    $panierUser[$id]['quantite'] += $item['quantite'];
                } else {
                    $panierUser[$id] = $item;
                }
            }

            // Sauvegarder et vider la session invité
            Cache::put("panier_{$userId}", $panierUser, now()->addHours(2));
            Session::forget('panier_invite');
        }
    }

    private function chargerPanier()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $panier = Cache::get("panier_{$userId}", []);

            // Ajout des mesures à chaque item du panier
            foreach ($panier as $modeleId => $item) {
                $mesures = Cache::get("mesures_user_{$userId}_modele_{$modeleId}", []);
                $item['mesures'] = $mesures;
                $panier[$modeleId] = $item;
            }
        } else {
            $panier = Session::get('panier_invite', []);

            // Si tu souhaites aussi gérer des mesures pour les invités (optionnel),
            // tu pourrais les stocker dans la session et les récupérer ici.
            foreach ($panier as $modeleId => $item) {
                $mesures = Session::get("mesures_invite_modele_{$modeleId}", []);
                $item['mesures'] = $mesures;
                $panier[$modeleId] = $item;
            }
        }

        $this->panier = $panier;
        $this->totalArticles = array_sum(array_column($this->panier, 'quantite'));
    }


    public function retirerDuPanier($modeleId)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $panier = Cache::get("panier_{$userId}", []);
        } else {
            $panier = Session::get('panier_invite', []);
        }

        if (isset($panier[$modeleId])) {
            if ($panier[$modeleId]['quantite'] > 1) {
                $panier[$modeleId]['quantite']--;
            } else {
                unset($panier[$modeleId]);
            }
        }

        if (Auth::check()) {
            Cache::put("panier_{$userId}", $panier, now()->addHours(2));
        } else {
            Session::put('panier_invite', $panier);
        }

        $this->chargerPanier();
    }

    public function viderPanier()
    {
        if (Auth::check()) {
            Cache::forget("panier_" . Auth::id());
        } else {
            Session::forget('panier_invite');
        }

        $this->chargerPanier();
    }

    public function render()
    {
        return view('livewire.panier', [
            'panier' => $this->panier,
            'totalArticles' => $this->totalArticles,
        ])->layout('layouts.test');
    }
}
