<?php

namespace App\Http\Controllers;

use App\Models\Attribut;
use App\Models\Modele;
use App\Models\Categorie;
use App\Models\Devis;
use App\Models\mesure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;


class ModeleController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Modele::class);
        $modeles = Modele::latest()->get();
        return view('modeles.index', compact('modeles'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Modele::class);
        $categories = Categorie::leaf()->get();
        $attributs = Attribut::with('valeurs')->get();
        $attributValeurs = collect();

        if ($request->has('devi_id')) {
            $devi = Devis::with('attributValeurs.attribut')->findOrFail($request->devi_id);
            $attributValeurs = $devi->attributValeurs;
        }
        return view('modeles.create', compact('categories', 'attributs','attributValeurs'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Modele::class);

        // Validation des données entrantes
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'stock' => 'nullable|boolean',
            'sur_commande' => 'nullable|boolean',
            'patron' => 'nullable|file|max:2048',
            'xml' => 'nullable|file|max:2048',
            'image' => 'nullable|image|max:2048',
            'attribut_valeurs' => 'nullable|array',
            'attribut_valeurs.*' => 'nullable|exists:attribut_valeurs,id',

        ]);

        // Création du modèle
        $modele = Modele::create([
            'nom' => $validatedData['nom'],
            'description' => $validatedData['description'] ?? null,
            'prix' => $validatedData['prix'],
            'categorie_id' => $validatedData['categorie_id'],
            'stock' => $request->has('stock'),
            'sur_commande' => $request->has('sur_commande'),
        ]);

        if ($request->hasFile('image')) {
            $imageName = 'modele-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('modeles', $imageName, 'public');
            $modele->image = $path;
        }

        // Lier les valeurs d'attributs sélectionnées
        if (!empty($validatedData['attribut_valeurs'])) {
            $valeurIds = array_filter($validatedData['attribut_valeurs']); // enlève les champs vides
            $modele->attributValeurs()->sync($valeurIds);
        }

        // Gestion du fichier patron (.val)
        if ($request->hasFile('patron')) {
            $patronName = "modele-{$modele->id}-patron.val";
            $path = $request->file('patron')->storeAs('patrons', $patronName, 'public');
            $modele->patron = $path;
        }

        // Gestion du fichier XML (.vit/.xml)
        if ($request->hasFile('xml')) {
            $xmlName = "modele-{$modele->id}-mesures.vit";
            $path = $request->file('xml')->storeAs('mesures', $xmlName, 'public');
            $modele->xml = $path;
        }

        $modele->save();

        return redirect()->route('modeles.index')->with('message', 'Modèle ajouté avec succès !');
    }



    public function show(Modele $modele)
    {
        $this->authorize('view', $modele);
        $modele->load([
            'categorie',
            'mesures',
            'attributValeurs.attribut' // pour charger nom de l'attribut lié à chaque valeur
        ]);

        $mesures = $modele->mesures ?? collect();

        return view('modeles.show', compact('modele', 'mesures'));
    }

    public function edit(Modele $modele)
    {
        $categories = Categorie::leaf()->get();

        // On récupère les attributs avec leurs valeurs
        $attributs = Attribut::with('valeurs')->get();

        $modele->load('attributValeurs');


        // Les valeurs actuellement liées au modèle
        $selectedValeurs = $modele->attributValeurs()->pluck('attribut_valeur_id')->toArray();

        return view('modeles.edit', compact('modele', 'categories', 'attributs', 'selectedValeurs'));
    }



    public function update(Request $request, Modele $modele)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'valeurs' => 'array',
            'valeurs.*' => 'exists:attribut_valeurs,id',
            'patron' => 'nullable|file|max:2048',
            'xml' => 'nullable|file|max:2048',
            'image' => 'nullable|image|max:2048',

        ]);

        $validatedData['stock'] = $request->has('stock') ? 1 : 0;
        $validatedData['sur_commande'] = $request->has('sur_commande') ? 1 : 0;

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne si elle existe
            if ($modele->image) {
                Storage::disk('public')->delete($modele->image);
            }
            $imageName = 'modele-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('modeles', $imageName, 'public');
            $modele->image = $path;
        }


        // Fichiers
        if ($request->hasFile('patron')) {
            Storage::disk('public')->delete($modele->patron);
            $patronName = "modele-{$modele->id}-patron.val";
            $modele->patron = $request->file('patron')->storeAs('patrons', $patronName, 'public');
        }

        if ($request->hasFile('xml')) {
            Storage::disk('public')->delete($modele->xml);
            $xmlName = "modele-{$modele->id}-mesures.vit";
            $modele->xml = $request->file('xml')->storeAs('mesures', $xmlName, 'public');
        }

        $modele->update([
            'nom' => $validatedData['nom'],
            'description' => $validatedData['description'],
            'prix' => $validatedData['prix'],
            'categorie_id' => $validatedData['categorie_id'],
            'stock' => $validatedData['stock'],
            'sur_commande' => $validatedData['sur_commande'],
            'patron' => $modele->patron,
            'xml' => $modele->xml,
        ]);

        // On synchronise les valeurs d'attributs avec la table pivot
        $modele->attributValeurs()->sync($validatedData['valeurs'] ?? []);

        return redirect()->route('modeles.index')->with('message', 'Modèle mis à jour avec succès !');
    }

    public function destroy(Modele $modele)
    {
        $this->authorize('delete', $modele);

        if ($modele->image) {
            Storage::disk('public')->delete($modele->image);
        }

        if ($modele->patron) {
            Storage::disk('public')->delete($modele->patron);
        }
        if ($modele->xml) {
            Storage::disk('public')->delete($modele->xml);
        }

        $modele->delete();

        return redirect()->route('modeles.index')->with('message', 'Modèle supprimé avec succès.');
    }
}
