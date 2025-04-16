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

        // Pré-remplir avec des valeurs stockées dans le cache (par nom)
        $cachedValues = Cache::get($this->cacheKey(), []);

        foreach ($this->mesures as $mesure) {
            $key = $mesure->label ?? $mesure->nom; // Utilise 'label' ou 'nom' selon ta colonne
            $this->values[$key] = $cachedValues[$key] ?? $mesure->valeur_par_defaut;
        }
    }

    public function save()
    {
        // Valider toutes les valeurs comme numériques
        foreach ($this->values as $key => $value) {
            if (!is_numeric($value) || $value < 0) {
                $this->addError("values.$key", "La valeur de $key doit être un nombre positif.");
            }
        }

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        // Sauvegarder dans le cache sous forme [label => valeur]
        Cache::put($this->cacheKey(), $this->values, now()->addHours(12));

        session()->flash('success', 'Vos mesures ont été enregistrées temporairement.');

        return redirect()->route('panier');
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
