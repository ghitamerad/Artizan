<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Modele;
use App\Models\Categorie;
use App\Models\Attribut;
use Illuminate\Support\Collection;

class Produits extends Component
{
    use WithPagination;

    public $categorieSelectionnee = null;
    public $valeursSelectionnees = [];
    public $afficherFiltres = false;


    protected $updatesQueryString = ['categorieSelectionnee', 'valeursSelectionnees'];

    public function updatingCategorieSelectionnee()
    {
        $this->resetPage();
    }

    public function updatingValeursSelectionnees()
    {
        $this->resetPage();
    }

    public function selectCategorie($id)
    {
        $this->categorieSelectionnee = $id;
    }

    public function getCategoriesActuellesProperty()
    {
        if ($this->categorieSelectionnee) {
            return Categorie::find($this->categorieSelectionnee)?->enfants ?? collect();
        }

        return Categorie::whereNull('categorie_id')->get(); // catégories racines
    }

    public function getModelesProperty()
    {
        $query = Modele::query();

        if ($this->categorieSelectionnee) {
            $query->where('categorie_id', $this->categorieSelectionnee);
        }

        if (!empty($this->valeursSelectionnees)) {
            $query->whereHas('attributValeurs', function ($q) {
                $q->whereIn('attribut_valeurs.id', $this->valeursSelectionnees);
            });
        }

        return $query->with('categorie')->paginate(6);
    }

       public function afficherFormulaireFiltres()
    {
        $this->afficherFiltres = !$this->afficherFiltres;
    }

    public function appliquerFiltres()
    {
        $this->resetPage();
    }

    public function reinitialiserFiltres()
    {
        $this->valeursSelectionnees = [];
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.produits', [
            'categoriesActuelles' => $this->categoriesActuelles,
            'attributs' => Attribut::with('valeurs')->get(),
            'modeles' => $this->modeles,
        ])->layout('layouts.test');
    }
}
