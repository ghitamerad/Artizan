<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use App\Models\Modele;
use App\Models\Commande;
use App\Models\DetailCommande;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Panier extends Component
{
    public $panier = [];

    protected $listeners = ['ajouterAuPanier'];

    public function mount()
    {
        $this->panier = Session::get('panier', []);
    }

    public function ajouterAuPanier($modeleId, $quantite = 1)
    {
        $modele = Modele::findOrFail($modeleId);

        if (isset($this->panier[$modeleId])) {
            $this->panier[$modeleId]['quantite'] += $quantite;
        } else {
            $this->panier[$modeleId] = [
                'id' => $modele->id,
                'nom' => $modele->nom,
                'prix_unitaire' => $modele->prix,
                'quantite' => $quantite,
            ];
        }

        Session::put('panier', $this->panier);
        $this->emit('panierMisAJour');
    }

    public function supprimerDuPanier($modeleId)
    {
        unset($this->panier[$modeleId]);
        Session::put('panier', $this->panier);
        $this->emit('panierMisAJour');
    }

    public function mettreAJourQuantite($modeleId, $quantite)
    {
        if ($quantite <= 0) {
            $this->supprimerDuPanier($modeleId);
        } else {
            $this->panier[$modeleId]['quantite'] = $quantite;
            Session::put('panier', $this->panier);
        }
    }

    public function passerCommande()
    {
        if (!Auth::check()) {
            session()->flash('message', 'Veuillez vous connecter pour passer une commande.');
            return;
        }

        if (empty($this->panier)) {
            session()->flash('message', 'Votre panier est vide.');
            return;
        }

        DB::beginTransaction();

        try {
            $commande = Commande::create([
                'user_id' => Auth::id(),
                'montant_total' => array_sum(array_map(fn ($item) => $item['prix_unitaire'] * $item['quantite'], $this->panier)),
                'statut' => 'en_attente',
            ]);

            foreach ($this->panier as $item) {
                DetailCommande::create([
                    'commande_id' => $commande->id,
                    'modele_id' => $item['id'],
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix_unitaire'],
                ]);
            }

            DB::commit();

            Session::forget('panier');
            $this->panier = [];

            session()->flash('success', 'Commande passée avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Une erreur est survenue lors de la commande.');
        }
    }


    public function render()
    {
        return view('livewire.panier', [
            'total' => array_sum(array_map(fn ($item) => $item['prix_unitaire'] * $item['quantite'], $this->panier)),
        ])->layout('layouts.app');;
    }
}
