<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Request;


class RechercheBar extends Component
{
    public $search = '';
    public $showInput = false;

    public function toggleInput()
    {
        $this->showInput = true;
    }


    public function mount()
    {
        $this->search = Request::get('search', '');
        $this->showInput = !empty($this->search);
    }

    public function redirectToSearch()
    {
        if (!empty($this->search)) {
            return redirect()->route('home', ['search' => $this->search]);
        }
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->showInput = false;

        // Redirige vers la route sans paramètre
        return redirect()->route('home');
    }


    public function render()
    {
        return view('livewire.recherche-bar')->layout('layouts.test');
    }
}
