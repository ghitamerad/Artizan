<?php

namespace App\Http\Controllers;

use App\Models\Attribut;
use App\Models\Modele;
use App\Models\Categorie;
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

    public function create()
    {
        $this->authorize('create', Modele::class);
        $categories = Categorie::all();
        $attributs = \App\Models\Attribut::all();
        return view('modeles.create', compact('categories', 'attributs'));
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
            'stock' => 'required|boolean',
            'sur_commande' => 'nullable|boolean',
            'patron' => 'nullable|file|max:2048',
            'xml' => 'nullable|file|max:2048',
            'attributs' => 'nullable|array',
            'attributs.*' => 'exists:attributs,id',
        ]);

        // Création du modèle dans la base de données
        $modele = Modele::create([
            'nom' => $validatedData['nom'],
            'description' => $validatedData['description'],
            'prix' => $validatedData['prix'],
            'categorie_id' => $validatedData['categorie_id'],
            'stock' => $validatedData['stock'],
            'sur_commande' => $validatedData['sur_commande'] ?? false,  // Assurer une valeur par défaut pour 'sur_commande'
        ]);

        // Si des attributs sont sélectionnés, on les lie au modèle via la table pivot
        if ($request->has('attributs')) {
            $modele->attributs()->sync($request->attributs);
        }

        // Gestion du fichier patron (si présent)
        if ($request->hasFile('patron')) {
            $patronName = "modele-{$modele->id}-patron.val";
            $modele->patron = $request->file('patron')->storeAs('patrons', $patronName, 'public');
        }

        // Gestion du fichier XML (si présent)
        if ($request->hasFile('xml')) {
            $xmlName = "modele-{$modele->id}-mesures.vit";
            $modele->xml = $request->file('xml')->storeAs('mesures', $xmlName, 'public');
        }

        // Sauvegarde du modèle (incluant les fichiers et les relations)
        $modele->save();

        // Redirection vers la liste des modèles avec un message de succès
        return redirect()->route('modeles.index')->with('message', 'Modèle ajouté avec succès !');
    }


    public function show(Modele $modele)
    {
        $this->authorize('view', $modele);
        $mesures = $modele->mesures ?? collect();
        return view('modeles.show', compact('modele', 'mesures'));
    }

    public function edit(Modele $modele)
    {
        $categories = Categorie::all();
        $attributs = Attribut::all(); // 👉 On les récupère ici
        return view('modeles.edit', compact('modele', 'categories', 'attributs'));
    }


    public function update(Request $request, Modele $modele)
    {
        $this->authorize('update', $modele);

        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'patron' => 'nullable|file|max:2048',
            'xml' => 'nullable|file|max:2048',
        ]);

            // Gérer les checkboxes (coché = 1, décoché = 0)
    $validatedData['stock'] = $request->has('stock') ? 1 : 0;
    $validatedData['sur_commande'] = $request->has('sur_commande') ? 1 : 0;

        $modele->attributs()->sync($request->input('attributs', []));


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

        ]);

        // Si les champs sont dans la requête, on les prend. Sinon, on garde la valeur existante :
$validatedData['stock'] = $request->has('stock') ? 1 : $modele->stock;
$validatedData['sur_commande'] = $request->has('sur_commande') ? 1 : $modele->sur_commande;

$modele->update($validatedData);


        return redirect()->route('modeles.index')->with('message', 'Modèle mis à jour avec succès !');
    }

    public function destroy(Modele $modele)
    {
        $this->authorize('delete', $modele);

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
