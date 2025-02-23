<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\modele;
use App\Models\categorie;
use Illuminate\Support\Facades\Auth;
use App\Models\Panier;



class Home extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategorie = ''; // Filtre par catégorie
    public $minPrix = null; // Filtre prix min
    public $maxPrix = null; // Filtre prix max

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategorie' => ['except' => ''],
        'minPrix' => ['except' => null],
        'maxPrix' => ['except' => null],
    ];

    public function updated($property)
    {
        if (in_array($property, ['search', 'selectedCategorie', 'minPrix', 'maxPrix'])) {
            $this->resetPage();
        }
    }

    public function ajouterAuPanier($modeleId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $modele = modele::findOrFail($modeleId);

        $panier = Panier::firstOrCreate(
            ['user_id' => Auth::id(), 'modele_id' => $modele->id],
            ['quantite' => 1]
        );

        if (!$panier->wasRecentlyCreated) {
            $panier->increment('quantite');
        }

        session()->flash('message', 'Modèle ajouté au panier !');
        $this->dispatch('panierMisAJour'); // Pour actualiser le panier si besoin
    }


    public function render()
{
    $categories = categorie::all();

    $modeles = modele::query()
        ->when($this->search, function ($query) {
            $query->where('nom', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        })
        ->when($this->selectedCategorie, function ($query) {
            $query->where('categorie_id', $this->selectedCategorie);
        })
        ->when($this->minPrix, function ($query) {
            $query->where('prix', '>=', $this->minPrix);
        })
        ->when($this->maxPrix, function ($query) {
            $query->where('prix', '<=', $this->maxPrix);
        })
        ->with('categorie')
        ->orderBy('created_at', 'desc')
        ->paginate(9);

    // Compter le nombre d'articles dans le panier de l'utilisateur
    $panierCount = Auth::check() ? Panier::where('user_id', Auth::id())->count() : 0;

    return view('livewire.home', compact('modeles', 'categories', 'panierCount'))->layout('layouts.app');
}
}
