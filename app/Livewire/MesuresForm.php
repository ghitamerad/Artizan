<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Mesure;
use App\Models\Modele;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MesuresForm extends Component
{
    public $modele;
    public $mesures = [];
    public $values = [];

        public function mount($modeleId)
        {
            $this->modele = Modele::findOrFail($modeleId);
            $this->mesures = Mesure::where('modele_id', $modeleId)->get();

            // Pré-remplir les valeurs avec cache ou valeurs par défaut
            $cachedValues = Cache::get($this->cacheKey(), []);
            foreach ($this->mesures as $mesure) {
                $this->values[$mesure->id] = $cachedValues[$mesure->id] ?? $mesure->valeur_par_defaut;
            }
        }

        public function save()
        {
            $this->validate([
                'values.*' => 'required|numeric|min:0',
            ]);

            Cache::put($this->cacheKey(), $this->values, now()->addHours(12));

            session()->flash('success', 'Vos mesures ont été enregistrées temporairement.');

        }

        private function cacheKey()
        {
            return 'mesures_user_' . Auth::id() . '_modele_' . $this->modele->id;
        }

        public function render()
        {
            return view('livewire.mesures-form')->layout('layouts.test');
        }


}
