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
        DetailCommande::where('id', $id)->update(['statut' => 'validee']);
        $this->voirCommandes();
    }

    public function refuser($id)
    {
        DetailCommande::where('id', $id)->update(['statut' => 'refuser']);
        $this->voirCommandes();
    }

    public function render()
    {
        return view('livewire.couturiere-dashboard')->layout('layouts.couturiere');
    }
}

