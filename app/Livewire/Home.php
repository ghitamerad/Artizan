<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Modele;
use App\Models\Categorie;
use Illuminate\Support\Facades\Auth;
use App\Models\Panier;

class Home extends Component
{
    use WithPagination;

    // Propriétés publiques liées aux filtres
    public $search = ''; // Texte recherché
    public $selectedCategorie = ''; // Filtrage par catégorie (désactivé ici)
    public $minPrix = null; // Filtrage prix min (désactivé ici)
    public $maxPrix = null; // Filtrage prix max (désactivé ici)

    // Permet d’ajouter les filtres dans l’URL
    protected $queryString = [
        'search' => ['except' => ''],
        // 'selectedCategorie' => ['except' => ''],
        // 'minPrix' => ['except' => null],
        // 'maxPrix' => ['except' => null],
    ];

    // Événement Livewire pour forcer le rafraîchissement
    protected $listeners = [
        'refreshComponent' => '$refresh',
        'searchUpdated' => 'setSearchTerm',
    ];

    public function setSearchTerm($value)
    {
        $this->search = $value;
        $this->resetPage();
    }

    // Réinitialise la pagination quand on modifie la recherche
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Fonctions désactivées (liées à filtres non utilisés)
    public function updatedSelectedCategorie() { $this->resetPage(); }
    public function updatedMinPrix() { $this->resetPage(); }
    public function updatedMaxPrix() { $this->resetPage(); }

    // Fonction de réinitialisation de tous les filtres (inutile ici)
    public function resetFiltres()
    {
        $this->search = '';
        $this->selectedCategorie = '';
        $this->minPrix = null;
        $this->maxPrix = null;
    }

public function mount()
{
    $this->search = request()->query('search', '');
}


    // Ajout d’un modèle au panier (fonctionnalité conservée)
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

    // Méthode principale pour afficher les modèles
    public function render()
    {
    $modeles = Modele::where('nom', 'like', '%' . $this->search . '%')
        ->orWhere('description', 'like', '%' . $this->search . '%')
        ->orderBy('created_at', 'desc')
        ->paginate(9);

    return view('livewire.home', [
        'modeles' => $modeles,
    ])->layout('layouts.test');
    }
}
