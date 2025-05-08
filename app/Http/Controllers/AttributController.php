<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribut;
use App\Models\AttributValeur;
use Illuminate\Support\Facades\Storage;


class AttributController extends Controller
{
    // Affiche tous les attributs
    public function index()
    {
        $attributs = Attribut::with('valeurs')->latest()->get();
        return view('attributs.index', compact('attributs'));
    }

    // Stocke un nouvel attribut
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'obligatoire' => 'nullable|boolean',
        ]);

        Attribut::create([
            'nom' => $request->nom,
            'obligatoire' => $request->has('obligatoire'),
        ]);

        return redirect()->route('attributs.index')->with('success', 'Attribut créé avec succès.');
    }

    // Affiche le formulaire de modification
    public function edit(Attribut $attribut)
    {
        return view('attributs.edit', compact('attribut'));
    }

    // Met à jour l’attribut
    public function update(Request $request, Attribut $attribut)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'obligatoire' => 'nullable|boolean',
        ]);

        $attribut->update([
            'nom' => $request->nom,
            'obligatoire' => $request->has('obligatoire'),
        ]);

        return redirect()->route('attributs.index')->with('success', 'Attribut mis à jour avec succès.');
    }

    // Supprime un attribut
    public function destroy(Attribut $attribut)
    {
        $attribut->delete();
        return redirect()->route('attributs.index')->with('success', 'Attribut supprimé.');
    }
}