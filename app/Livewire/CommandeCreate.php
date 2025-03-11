<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Commande;
use App\Models\DetailCommande;
use App\Models\Modele;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommandeCreate extends Component
{
    public $modeles;
    public $couturieres;
    public $selectedModeles = [];

    public function mount()
    {
        $this->modeles = Modele::all();
        $this->couturieres = User::where('role', 'couturiere')->get();
        $this->addModele(); // Ajoute un modèle par défaut
    }

    public function addModele()
    {
        $this->selectedModeles[] = [
            'id' => '',
            'quantite' => 1,
            'custom' => 0,
            'user_id' => ''
        ];
    }

    public function removeModele($index)
    {
        unset($this->selectedModeles[$index]);
        $this->selectedModeles = array_values($this->selectedModeles); // Réindexation
    }

    public function store()
    {
        $this->validate([
            'selectedModeles' => 'required|array|min:1',
            'selectedModeles.*.id' => 'required|exists:modeles,id',
            'selectedModeles.*.quantite' => 'required|integer|min:1',
            'selectedModeles.*.custom' => 'required|boolean',
            'selectedModeles.*.user_id' => 'nullable|exists:users,id',
        ]);

        DB::beginTransaction();

        try {
            $commande = Commande::create([
                'user_id' => Auth::id(),
                'montant_total' => $this->calculerTotal(),
                'statut' => 'en_attente',
            ]);

            foreach ($this->selectedModeles as $item) {
                DetailCommande::create([
                    'commande_id' => $commande->id,
                    'modele_id' => $item['id'],
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => Modele::find($item['id'])->prix,
                    'custom' => (bool) $item['custom'], // Forcer en booléen
                    'user_id' => $item['user_id'] ?: null, // Peut être null si non attribué
                ]);
            }

            DB::commit();
            session()->flash('success', 'Commande enregistrée avec succès.');
            return redirect()->route('commandes.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    private function calculerTotal()
    {
        return collect($this->selectedModeles)->sum(function ($item) {
            return Modele::find($item['id'])->prix * $item['quantite'];
        });
    }

    public function render()
    {
        return view('livewire.commande-create')->layout('layouts.admin');
    }
}
