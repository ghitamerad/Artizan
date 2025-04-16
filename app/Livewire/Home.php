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

    public $search = '';
    public $selectedCategorie = ''; // ID de la catégorie sélectionnée
    public $minPrix = null;
    public $maxPrix = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategorie' => ['except' => ''],
        'minPrix' => ['except' => null],
        'maxPrix' => ['except' => null],
    ];

    protected $listeners = ['refreshComponent' => '$refresh']; // Écouteur pour recharger le composant

    public function updatedSearch()
    {
        $this->resetPage(); // Réinitialise la pagination
    }

    public function updatedSelectedCategorie()
    {
        $this->resetPage();
    }

    public function updatedMinPrix()
    {
        $this->resetPage();
    }

    public function updatedMaxPrix()
    {
        $this->resetPage();
    }

    public function resetFiltres()
{
    $this->search = '';
    $this->selectedCategorie = '';
    $this->minPrix = null;
    $this->maxPrix = null;
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

    public function render()
    {
        $query = Modele::query();

        // Recherche par nom
        if (trim($this->search) !== '') {
            $query->where('nom', 'like', '%' . $this->search . '%');
        }

        // Filtrage par catégorie
        if (!empty($this->selectedCategorie)) {
            $query->where('categorie_id', $this->selectedCategorie);
        }

        // Filtrage par prix
        if (!is_null($this->minPrix)) {
            $query->where('prix', '>=', $this->minPrix);
        }
        if (!is_null($this->maxPrix)) {
            $query->where('prix', '<=', $this->maxPrix);
        }

        // Résultats
        $modeles = $query->whereNotNull('prix')
            ->with('categorie')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        $categories = Categorie::all();

        return view('livewire.home', compact('modeles', 'categories'))
            ->layout('layouts.test');
    }

}
