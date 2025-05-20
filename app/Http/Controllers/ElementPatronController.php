<?php

namespace App\Http\Controllers;

use App\Models\AttributValeur;
use App\Models\categorie;
use App\Models\ElementPatron;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ElementPatronController extends Controller
{
    public function index()
    {
        $elements = ElementPatron::with(['categorie', 'attributValeur'])->get();
        return view('element_patrons.index', compact('elements'));
    }

    public function create()
    {
        $categories = categorie::leaf()->get();
        $valeurs = AttributValeur::all();
        return view('element_patrons.create', compact('categories', 'valeurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fichier_patron' => 'required|file',
            'categorie_id' => 'required|exists:categories,id',
            'attribut_valeur_id' => 'required|exists:attribut_valeurs,id',
        ]);

        $file = $request->file('fichier_patron');
        $valeur = AttributValeur::findOrFail($request->attribut_valeur_id);
        $categorie = Categorie::findOrFail($request->categorie_id);

        // Générer le nom du fichier : categorie_nom + attribut_valeur_nom
        $filename = Str::slug($categorie->nom . '_' . $valeur->valeur) . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('element-patrons', $filename, 'public');

        ElementPatron::create([
            'fichier_patron' => $path,
            'categorie_id' => $request->categorie_id,
            'attribut_valeur_id' => $request->attribut_valeur_id,
        ]);

        return redirect()->route('element-patrons.index')->with('success', 'Élément ajouté avec succès.');
    }


    public function show(ElementPatron $elementPatron)
    {
        return view('element_patrons.show', compact('elementPatron'));
    }


    public function edit(ElementPatron $elementPatron)
    {
        $categories = Categorie::leaf()->get();
        $valeurs = AttributValeur::all();
        return view('element_patrons.edit', compact('elementPatron', 'categories', 'valeurs'));
    }

    public function update(Request $request, ElementPatron $elementPatron)
    {
        $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'attribut_valeur_id' => 'required|exists:attribut_valeurs,id',
            'fichier_patron' => 'nullable|file',
        ]);

        $elementPatron->categorie_id = $request->categorie_id;
        $elementPatron->attribut_valeur_id = $request->attribut_valeur_id;

        if ($request->hasFile('fichier_patron')) {
            $file = $request->file('fichier_patron');
            $valeur = AttributValeur::findOrFail($request->attribut_valeur_id);
            $categorie = Categorie::findOrFail($request->categorie_id);

            $filename = Str::slug($categorie->nom . '_' . $valeur->valeur) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('element-patrons', $filename, 'public');

            $elementPatron->fichier_patron = $path;
        }

        $elementPatron->save();

        return redirect()->route('element-patrons.index')->with('success', 'Élément modifié avec succès.');
    }


    public function destroy(ElementPatron $elementPatron)
    {
        // Supprimer le fichier du disque s'il existe
        if ($elementPatron->fichier_patron && Storage::disk('public')->exists($elementPatron->fichier_patron)) {
            Storage::disk('public')->delete($elementPatron->fichier_patron);
        }

        // Supprimer l'enregistrement de la base de données
        $elementPatron->delete();

        return redirect()->route('element-patrons.index')->with('success', 'Élément supprimé.');
    }
}
