<?php

namespace App\Livewire;


use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Modele;
use App\Models\Categorie;

class SurMesure extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategorie = '';
    public $minPrix;
    public $maxPrix;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Modele::where('sur_commande', true);

        if ($this->search) {
            $query->where('nom', 'like', '%' . $this->search . '%');
        }

        if ($this->selectedCategorie) {
            $query->where('categorie_id', $this->selectedCategorie);
        }

        if ($this->minPrix) {
            $query->where('prix', '>=', $this->minPrix);
        }

        if ($this->maxPrix) {
            $query->where('prix', '<=', $this->maxPrix);
        }

        return view('livewire.sur-mesure', [
            'modeles' => $query->paginate(9),
            'categories' => Categorie::all(),
        ])->layout('layouts.test');
    }
}
