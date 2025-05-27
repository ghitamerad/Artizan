<?php

namespace App\Livewire;

use App\Models\Attribut;
use App\Models\Categorie;
use App\Models\Modele;
use Livewire\Component;

class QuestionnaireSelector extends Component
{
    public $modelesFiltres = [];

    public $categorieSelectionnees = []; // Tableau des catégories sélectionnées
    public $categoriesActuelles; // Liste des sous-catégories à ce niveau

    public $categorieFinale; // La catégorie leaf sélectionnée

    public $attributs = []; // Attributs liés à cette catégorie
    public $selectedValeurs = []; // attribut_id => attribut_valeur_id

    public function mount()
    {
        $this->categoriesActuelles = Categorie::whereNull('categorie_id')->get(); // catégories racines
    }

    public function selectCategorie($categorieId)
    {
        $categorie = Categorie::findOrFail($categorieId);
        $this->categorieSelectionnees[] = $categorie;
        $sousCategories = $categorie->enfants;

        if ($sousCategories->isEmpty()) {
            $this->categorieFinale = $categorie;
            $this->chargerAttributs();
        } else {
            $this->categoriesActuelles = $sousCategories;
        }
    }

    public function retour()
    {
        array_pop($this->categorieSelectionnees);
        $this->categorieFinale = null;
        $this->selectedValeurs = [];

        if (empty($this->categorieSelectionnees)) {
            $this->categoriesActuelles = Categorie::whereNull('categorie_id')->get();
        } else {
            $this->categoriesActuelles = $this->categorieSelectionnees[count($this->categorieSelectionnees) - 1]->enfants;
        }
    }

    public function getImageUrlAttribute()
{
    return $this->image ? asset('storage/' . $this->image) : asset('images/default-category.png');
}


    public function chargerAttributs()
    {
        // 1. On récupère tous les modèles liés à cette catégorie
        $modeles = Modele::where('categorie_id', $this->categorieFinale->id)->with('attributValeurs.attribut')->get();

        // 2. On collecte tous les attributs uniques via les modèles
        $attributsIds = collect();
        foreach ($modeles as $modele) {
            foreach ($modele->attributValeurs as $valeur) {
                $attributsIds->push($valeur->attribut_id);
            }
        }

        $attributsIds = $attributsIds->unique();

        // 3. On charge tous les attributs avec leurs valeurs possibles
        $attributs = Attribut::with('valeurs')->whereIn('id', $attributsIds)->get();

        // 4. On formate les données pour l'affichage
        $this->attributs = [];
        foreach ($attributs as $attribut) {
            $this->attributs[$attribut->id] = [
                'nom' => $attribut->nom,
    'valeurs' => $attribut->valeurs->keyBy('id')->toArray(), // tableaux complets (nom + image)

            ];
        }
        $this->dispatch('questionnaireTermine', $this->categorieFinale, $this->attributs, $this->selectedValeurs);

    }

    public function genererResultats()
{
    $valeursIds = array_values($this->selectedValeurs);

    $this->modelesFiltres = Modele::where('categorie_id', $this->categorieFinale->id)
        ->whereHas('attributValeurs', function ($query) use ($valeursIds) {
            $query->whereIn('attribut_valeur_id', $valeursIds);
        })
        ->with('categorie')
        ->get();
}



    public function render()
    {
        return view('livewire.questionnaire-selector')->layout('layouts.test');
    }
}
