<?php

namespace App\Http\Controllers;

use App\Models\AttributValeur;
use App\Models\categorie;
use App\Models\ElementPatron;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
    
        // Nettoyer le nom (ex : "col en V" → "col-en-v")
        $filename = Str::slug($valeur->nom) . '.' . $file->getClientOriginalExtension();
    
        // Stocker le fichier avec un nom personnalisé
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

        if ($request->hasFile('fichier_patron')) {
            $file = $request->file('fichier_patron');
            $valeur = AttributValeur::findOrFail($request->attribut_valeur_id);
    
            $filename = Str::slug($valeur->nom) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('element-patrons', $filename, 'public');
    
            $elementPatron->fichier_patron = $path;
        }

        $elementPatron->categorie_id = $request->categorie_id;
        $elementPatron->attribut_valeur_id = $request->attribut_valeur_id;
        $elementPatron->save();

        return redirect()->route('element-patrons.index')->with('success', 'Élément modifié avec succès.');
    }

    public function destroy(ElementPatron $elementPatron)
    {
        $elementPatron->delete();
        return redirect()->route('element-patrons.index')->with('success', 'Élément supprimé.');
    }
}
