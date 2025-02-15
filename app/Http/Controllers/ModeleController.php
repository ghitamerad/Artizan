<?php
namespace App\Http\Controllers;

use App\Models\modele;
use App\Models\categorie;
use App\Http\Requests\StoreModeleRequest;
use App\Http\Requests\UpdateModeleRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class ModeleController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des modèles.
     */
    public function index()
    {
        $this->authorize('viewAny', modele::class);
        $modeles = modele::all();
        return view('modeles.index', compact('modeles'));
    }

    /**
     * Affiche le formulaire de création d'un modèle.
     */
    public function create()
    {
        $this->authorize('create', modele::class);
        $categories = categorie::all(); // Récupère toutes les catégories
        return view('modeles.create', compact('categories'));
    }

    /**
     * Enregistre un modèle en base de données.
     */
    public function store(StoreModeleRequest $request)
    {
        $this->authorize('create', modele::class);

        // Récupération des données validées
        $data = $request->validated();

        // Gestion du fichier 'patron'
        if ($request->hasFile('patron')) {
            $data['patron'] = $request->file('patron')->store('patrons', 'public');
        }

        // Gestion du fichier XML
        if ($request->hasFile('xml')) {
            $data['xml'] = $request->file('xml')->store('xmls', 'public');
        }

        // Création du modèle avec **tous les champs**
        modele::create([
            'categorie_id' => $data['categorie_id'],
            'nom' => $data['nom'],
            'description' => $data['description'] ?? null,
            'prix' => $data['prix'],
            'patron' => $data['patron'] ?? null,
            'xml' => $data['xml'] ?? null,
        ]);

        return redirect()->route('modeles.index')->with('message', 'Modèle ajouté avec succès.');
    }

    /**
     * Affiche les détails d'un modèle.
     */
    public function show(modele $modele)
    {
        $this->authorize('view', $modele);
        return view('modeles.show', compact('modele'));
    }

    /**
     * Affiche le formulaire d'édition d'un modèle.
     */
    public function edit(modele $modele)
    {
        $this->authorize('update', $modele);
        $categories = categorie::all();
        return view('modeles.edit', compact('modele', 'categories'));
    }

    /**
     * Met à jour un modèle en base de données.
     */
    public function update(UpdateModeleRequest $request, modele $modele)
    {
        $this->authorize('update', $modele);

        // Récupération des données validées
        $data = $request->validated();

        // Gestion du fichier 'patron' (mise à jour si un nouveau fichier est envoyé)
        if ($request->hasFile('patron')) {
            if ($modele->patron) {
                Storage::disk('public')->delete($modele->patron);
            }
            $data['patron'] = $request->file('patron')->store('patrons', 'public');
        }

        // Gestion du fichier XML
        if ($request->hasFile('xml')) {
            if ($modele->xml) {
                Storage::disk('public')->delete($modele->xml);
            }
            $data['xml'] = $request->file('xml')->store('xmls', 'public');
        }

        // Mise à jour du modèle avec **tous les champs**
        $modele->update([
            'categorie_id' => $data['categorie_id'],
            'nom' => $data['nom'],
            'description' => $data['description'] ?? null,
            'prix' => $data['prix'],
            'patron' => $data['patron'] ?? $modele->patron,
            'xml' => $data['xml'] ?? $modele->xml,
        ]);

        return redirect()->route('modeles.index')->with('message', 'Modèle mis à jour avec succès.');
    }

    /**
     * Supprime un modèle.
     */
    public function destroy(modele $modele)
    {
        $this->authorize('delete', $modele);

        // Supprimer les fichiers associés
        if ($modele->patron) {
            Storage::disk('public')->delete($modele->patron);
        }
        if ($modele->xml) {
            Storage::disk('public')->delete($modele->xml);
        }

        // Suppression du modèle
        $modele->delete();

        return redirect()->route('modeles.index')->with('message', 'Modèle supprimé avec succès.');
    }
}
