<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\modele;

class ShowModele extends Component
{
    public modele $modele;

    public function mount($id)
    {
        $this->modele = modele::with('categorie')->findOrFail($id);
    }

    public function ajouterAuPanier()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Logique d'ajout au panier
        session()->flash('message', 'Modèle ajouté au panier !');
    }

    private function extraireMesuresVit($xmlContent)
{
    $xml = simplexml_load_string($xmlContent);
    $mesures = [];

    foreach ($xml->{'body-measurements'}->m as $mesure) {
        $nom = (string) $mesure['name'];
        $valeur = (float) $mesure['value'];
        $mesures[] = ['nom' => $nom, 'valeur' => $valeur];
    }

    return $mesures;
}

public function afficherFormulaireMesures()
{
    $xmlContent = $this->modele->xml;

    if (!$xmlContent) {
        session()->flash('error', "Aucune fiche de mesures disponible pour ce modèle.");
        return;
    }

    $this->mesures = $this->extraireMesuresVit($xmlContent);

    return redirect()->route('mesures.formulaire', ['id' => $this->modele->id]);
}


    public function render()
    {
        return view('livewire.show-modele')->layout('layouts.guest2');
    }
} 