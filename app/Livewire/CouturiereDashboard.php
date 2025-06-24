<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DetailCommande;
use App\Services\GeneratePatronService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\CommandeReponseCouturiere;
use Illuminate\Support\Facades\Notification;


class CouturiereDashboard extends Component
{
    public $commandes;
    public $titre = " Commandes en attente";

    public function mount()
    {
        $this->voirCommandes();
    }

    public function voirCommandes()
    {
        $this->titre = " Commandes en attente";
        $this->commandes = DetailCommande::where('user_id', Auth::id())
            ->where('statut', 'Null')
            ->with(['modele', 'commande.user'])
            ->latest()
            ->get();
    }

    public function voirCommandesAcceptees()
    {
        $this->titre = "Commandes acceptées";
        $this->commandes = DetailCommande::where('user_id', Auth::id())
            ->whereIn('statut', ['validee', 'refuser'])
            ->with(['modele', 'commande.user'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function accepter($id, GeneratePatronService $generatePatronService)
    {
        $detailCommande = DetailCommande::findOrFail($id);
        $detailCommande->update(['statut' => 'validee']);

        //fonction qui genere le patron
        // $generatePatronService->customPattern($id);

        $commande = $detailCommande->commande;
        $tousValides = $commande->details()
            ->where('custom', true)
            ->where('statut', '!=', 'validee')
            ->doesntExist();

        if ($tousValides) {
            $commande->update(['statut' => 'validee']);
        }

        // Notifier la responsable
        $responsables = \App\Models\User::where('role', 'gerante')->get();
        Notification::send($responsables, new CommandeReponseCouturiere($detailCommande, 'acceptée'));

        // Génération du patron (sans téléchargement)
        $service = new GeneratePatronService();
        $result = $service->generatePattern($detailCommande->id);

        if ($result) {
            // Peut-être un petit message ou mise à jour d'état
            session()->flash('message', 'Commande acceptée et patron généré.');
        } else {
            session()->flash('error', 'Erreur lors de la génération du patron.');
        }
        session()->flash('success', '✅ La commande a été acceptée avec succès !');
        return redirect()->route('couturiere.commandes');
    }

    public function refuser($id)
    {
        $detailCommande = DetailCommande::findOrFail($id);
        $detailCommande->update(['statut' => 'refuser']);

        $commande = $detailCommande->commande;
        $tousRefuses = $commande->details()
            ->where('custom', true)
            ->where('statut', '!=', 'refuser')
            ->doesntExist();

        if ($tousRefuses) {
            $commande->update(['statut' => 'en_attente']);
        }

        // Notifier la responsable
        $responsables = \App\Models\User::where('role', 'gerante')->get();
        Notification::send($responsables, new CommandeReponseCouturiere($detailCommande, 'refusée'));

        session()->flash('error', '❌ La commande a été refusée.');

        // Mettre à jour la liste sans recharger la page
        $this->voirCommandes();
        $this->dispatch('refreshComponent');
    }

    public function render()
    {
        return view('livewire.couturiere-dashboard')->layout('layouts.admin');
    }
}
