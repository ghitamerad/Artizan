<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DetailCommande;
use Illuminate\Support\Facades\Auth;

class CouturiereDashboard extends Component
{
    public $commandes;
    public $titre = "📋 Commandes en attente";

    public function mount()
    {
        $this->voirCommandes();
    }

    public function voirCommandes()
    {
        $this->titre = "📋 Commandes en attente";
        $this->commandes = DetailCommande::where('user_id', Auth::id())
            ->where('statut', 'Null')
            ->with(['modele', 'commande'])
            ->get();
    }

    public function voirCommandesAcceptees()
    {
        $this->titre = "✅ Commandes acceptées";
        $this->commandes = DetailCommande::where('user_id', Auth::id())
            ->whereIn('statut', ['validee', 'refuser'])
            ->with(['modele', 'commande.client'])
            ->get();
    }

    public function accepter($id)
    {
        $detailCommande = DetailCommande::findOrFail($id);
        $detailCommande->update(['statut' => 'validee']);

        $commande = $detailCommande->commande;

        // Vérifier si tous les détails "custom=true" sont validés
        $tousValides = $commande->details()
                        ->where('custom', true)
                        ->where('statut', '!=', 'validee')
                        ->doesntExist();

        if ($tousValides) {
            $commande->update(['statut' => 'validee']);
        }

        $this->voirCommandes();
    }

    public function refuser($id)
    {
        $detailCommande = DetailCommande::findOrFail($id);
        $detailCommande->update(['statut' => 'refuser']);

        $commande = $detailCommande->commande;

        // Vérifier si tous les détails "custom=true" sont refusés
        $tousRefuses = $commande->details()
                        ->where('custom', true)
                        ->where('statut', '!=', 'refuser')
                        ->doesntExist();

        if ($tousRefuses) {
            $commande->update(['statut' => 'refuser']);
        }

        $this->voirCommandes();
    }


    public function render()
    {
        return view('livewire.couturiere-dashboard')->layout('layouts.couturiere');
    }
}

