<?php

namespace App\Livewire;

use Livewire\Component;

class RechercheBar extends Component
{
    public $search = '';

    public function redirectToSearch()
    {
        if (!empty($this->search)) {
            return redirect()->route('home', ['search' => $this->search]);
        }
    }



    public function render()
    {
        return view('livewire.recherche-bar');
    }
}
