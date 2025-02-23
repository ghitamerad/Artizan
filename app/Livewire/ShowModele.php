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

    public function render()
    {
        return view('livewire.show-modele')->layout('layouts.guest2');
    }
}
