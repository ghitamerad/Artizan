<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Modele;
use Illuminate\Support\Facades\Storage;

class CreateModele extends Component
{
    use WithFileUploads;

    public $nom;
    public $description;
    public $prix;
    public $categorie_id;
    public $patron;
    public $xml;

    public function createModele()
    {
        $this->authorize('create', modele::class); // Vérifie la policy

        $this->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'patron' => 'required|file|max:2048', // Patron en .val
            'xml' => 'required|file|max:2048', // Mesures en .vit ou .xml
        ]);

        $patronPath = $this->patron->store('modeles', 'public');
        $xmlPath = $this->xml->store('modeles', 'public');

        Modele::create([
            'nom' => $this->nom,
            'description' => $this->description,
            'prix' => $this->prix,
            'categorie_id' => $this->categorie_id,
            'patron' => $patronPath,
            'xml' => $xmlPath,
        ]);

        session()->flash('message', 'Modèle ajouté avec succès !');
        $this->reset(['nom', 'description', 'prix', 'categorie_id', 'patron', 'xml']);
    }

    public function generateAndDownloadPatron($id)
{
    $modele = Modele::findOrFail($id);

    // Récupérer les chemins des fichiers depuis la base de données
    $valFilePath = storage_path("app/public/{$modele->patron}");
    $vitFilePath = storage_path("app/public/{$modele->xml}");
    $outputDirectory = storage_path("app/public/modeles/"); // Dossier de sortie
    $outputFilePath = $outputDirectory . "{$modele->nom}.pdf"; // Fichier PDF généré

    // Vérifier que les fichiers existent
    if (!file_exists($valFilePath) || !file_exists($vitFilePath)) {
        return back()->with('error', 'Fichier patron ou mesures introuvable.');
    }

    // Commande pour exécuter Valentina et exporter le fichier
    $valentinaPath = 'C:\Program Files (x86)\Valentina\valentina.exe'; // Chemin de Valentina
    $command = "\"$valentinaPath\" -b SELMA -d \"$outputDirectory\" -f 0 -m \"$vitFilePath\" \"$valFilePath\"";

    // Exécution de la commande
    system($command, $resultCode);

    // Vérifier si la commande a réussi
    if ($resultCode !== 0 || !file_exists($outputFilePath)) {
        return back()->with('error', 'Erreur lors de la génération du patron.');
    }

    // Retourner le fichier en téléchargement
    return response()->download($outputFilePath);
}


    public function render()
    {
        return view('livewire.create-modele', [
            'categories' => \App\Models\Categorie::all(),
        ])->layout('layouts.guest2');
    }
}

