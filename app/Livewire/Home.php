<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Modele;
use App\Models\Categorie;
use App\Models\Attribut;
use App\Models\Panier;
use Illuminate\Support\Facades\Auth;

class Home extends Component
{
    use WithPagination;

    // Filtres
    public $search = '';
    public $categorieSelectionnee = null;
    public $valeursSelectionnees = [];
    public $afficherFiltres = false;
        public $filtre = null;


    // Query string
    protected $queryString = [
        'search' => ['except' => ''],
        'categorieSelectionnee' => ['except' => null],
        'valeursSelectionnees' => ['except' => []],
        'filtre',
    ];

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'searchUpdated' => 'setSearchTerm',
    ];

    public function mount()
    {
        $this->search = request()->query('search', '');
                $this->filtre = request()->get('filtre');

    }

    public function setSearchTerm($value)
    {
        $this->search = $value;
        $this->resetPage();
    }
    public function filtrerParType($type)
{
    if (in_array($type, ['pretaporter', 'surmesure'])) {
        $this->filtre = $type;
    } else {
        $this->filtre = null;
    }

    $this->resetPage();
}


    public function updatedSearch()
    {
        $this->resetPage();
    }

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
        $this->resetPage();
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

    public function ajouterAuPanier($modeleId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $modele = Modele::findOrFail($modeleId);

        $panier = Panier::firstOrCreate(
            ['user_id' => Auth::id(), 'modele_id' => $modele->id],
            ['quantite' => 1]
        );

        if (!$panier->wasRecentlyCreated) {
            $panier->increment('quantite');
        }

        session()->flash('message', 'Modèle ajouté au panier !');
        $this->dispatch('panierMisAJour');
    }

    public function getCategoriesActuellesProperty()
    {
        if ($this->categorieSelectionnee) {
            $categorie = Categorie::with('enfants')->find($this->categorieSelectionnee);

            // Si la catégorie a des enfants, on les affiche
            if ($categorie && $categorie->enfants->isNotEmpty()) {
                return $categorie->enfants;
            }

            // Si c'est une feuille => on n'affiche rien
            return null;
        }

        // Aucune catégorie sélectionnée : on affiche les racines
        return Categorie::whereNull('categorie_id')->get();
    }

    public function getModelesProperty()
    {
        $query = Modele::query();

         if ($this->filtre === 'pretaporter') {
            $query->where('sur_commande', false);
        } elseif ($this->filtre === 'surmesure') {
            $query->where('sur_commande', true);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nom', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categorieSelectionnee) {
            $query->where('categorie_id', $this->categorieSelectionnee);
        }

        if (!empty($this->valeursSelectionnees)) {
            $query->whereHas('attributValeurs', function ($q) {
                $q->whereIn('attribut_valeurs.id', $this->valeursSelectionnees);
            });
        }

        return $query->with('categorie')->orderBy('created_at', 'desc')->paginate(9);
    }

    public function render()
    {
        return view('livewire.home', [
            'categoriesActuelles' => $this->categoriesActuelles,
            'attributs' => Attribut::with('valeurs')->get(),
            'modeles' => $this->modeles,
        ])->layout('layouts.test');
    }
}
