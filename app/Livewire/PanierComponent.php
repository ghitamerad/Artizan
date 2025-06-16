<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Modele;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On; // (si nécessaire pour écouter des événements)

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

            foreach ($panier as $modeleId => $item) {
                $mesures = Cache::get("mesures_user_{$userId}_modele_{$modeleId}", []);
                $item['mesures'] = $mesures;

                // ✅ Ajout de l'image
                $modele = Modele::find($modeleId);
                $item['image'] = $modele?->image;

                $panier[$modeleId] = $item;
            }
        } else {
            $panier = Session::get('panier_invite', []);

            foreach ($panier as $modeleId => $item) {
                $mesures = Session::get("mesures_invite_modele_{$modeleId}", []);
                $item['mesures'] = $mesures;

                // ✅ Ajout de l'image aussi pour invités
                $modele = Modele::find($modeleId);
                $item['image'] = $modele?->image;

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

        $this->dispatch('panierMisAJour', $this->totalArticles);
    }

    public function viderPanier()
    {
        if (Auth::check()) {
            Cache::forget("panier_" . Auth::id());
        } else {
            Session::forget('panier_invite');
        }

        $this->chargerPanier();
        $this->dispatch('panierMisAJour', 0);
    }

    public function render()
    {
        return view('livewire.panier', [
            'panier' => $this->panier,
            'totalArticles' => $this->totalArticles,
        ])->layout('layouts.test');
    }
}
