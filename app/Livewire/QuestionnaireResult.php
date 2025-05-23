<?php

namespace App\Livewire;

use Livewire\Component;

class QuestionnaireResult extends Component
{
public $categorieFinale;
    public $attributs = [];
    public $selectedValeurs = [];

    protected $listeners = ['questionnaireTermine' => 'setResultat'];

    public function setResultat($categorieFinale, $attributs, $selectedValeurs)
    {
        $this->categorieFinale = (object) $categorieFinale;
        $this->attributs = $attributs;
        $this->selectedValeurs = $selectedValeurs;
    }

    public function render()
    {
        return view('livewire.questionnaire-result')->layout('layouts.test');
    }
}
