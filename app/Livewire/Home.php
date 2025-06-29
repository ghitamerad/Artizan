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

    public $search = '';
    public $categorieSelectionnee = null;
    public $valeursSelectionnees = [];
    public $afficherFiltres = false;
    public $filtre = null;

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

    public function resetFiltres()
    {
        $this->valeursSelectionnees = [];
        $this->categorieSelectionnee = null;
        $this->filtre = null;
        $this->afficherFiltres = false;
        $this->appliquerFiltres();
    }

    public function setSearchTerm($value)
    {
        $this->search = $value;
        $this->resetPage();
    }

    public function filtrerParType($type)
    {
        $this->filtre = in_array($type, ['pretaporter', 'surmesure']) ? $type : null;
        $this->resetPage();
    }

    public function updatedSearch() { $this->resetPage(); }
    public function updatingCategorieSelectionnee() { $this->resetPage(); }
    public function updatingValeursSelectionnees() { $this->resetPage(); }

    public function selectCategorie($id)
    {
        $this->categorieSelectionnee = $id;
        $this->resetPage();
    }

    public function afficherFormulaireFiltres()
    {
        $this->afficherFiltres = !$this->afficherFiltres;
    }

    public function appliquerFiltres() { $this->resetPage(); }
    public function reinitialiserFiltres() { $this->valeursSelectionnees = []; $this->resetPage(); }

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

    // ✅ Méthode récursive pour récupérer tous les IDs enfants
    public function getCategorieAvecEnfants($categorieId)
    {
        $ids = [$categorieId];
        $categorie = Categorie::with('enfants')->find($categorieId);

        if ($categorie) {
            foreach ($categorie->enfants as $enfant) {
                $ids = array_merge($ids, $this->getCategorieAvecEnfants($enfant->id));
            }
        }

        return $ids;
    }

    // ✅ Carrousel des catégories
    public function getCategoriesActuellesProperty()
    {
        if ($this->categorieSelectionnee) {
            $categorie = Categorie::with('enfants')->find($this->categorieSelectionnee);
return ($categorie && $categorie->enfants->isNotEmpty())
    ? $categorie->enfants->map(function ($cat) {
        $cat->has_children = $cat->enfants->isNotEmpty(); // Ajoute ce champ manuellement
        return $cat;
    })
    : null;
        }

        return Categorie::whereNull('categorie_id')->get();
    }

    // ✅ Liste filtrée des modèles
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

        // ✅ Appliquer catégorie + enfants
        if ($this->categorieSelectionnee) {
            $ids = $this->getCategorieAvecEnfants($this->categorieSelectionnee);
            $query->whereIn('categorie_id', $ids);
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

