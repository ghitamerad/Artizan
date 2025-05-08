<?php

namespace App\Http\Controllers;

use App\Models\Attribut;
use App\Models\AttributValeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttributValeurController extends Controller
{
 // Formulaire d'ajout
 public function create(Attribut $attribut)
 {
     return view('valeurs.create', compact('attribut'));
 }

 // Enregistrement de la valeur
 public function store(Request $request)
 {
     $request->validate([
         'nom' => 'required|string|max:255',
         'attribut_id' => 'required|exists:attributs,id',
         'image' => 'nullable|image|max:2048',
         'custom' => 'nullable|boolean',
     ]);

     $data = [
         'nom' => $request->nom,
         'attribut_id' => $request->attribut_id,
         'custom' => $request->has('custom'),
     ];

     // Enregistrement de l'image si présente
     if ($request->hasFile('image')) {
         $data['image'] = $request->file('image')->store('attribut_valeurs', 'public');
     }

     AttributValeur::create($data);

     return redirect()->route('attributs.index')->with('success', 'Valeur ajoutée avec succès.');
 }


    // Affiche le formulaire de modification d'une valeur
    public function edit(AttributValeur $valeur)
    {
        return view('valeurs.edit', compact('valeur'));
    }

    // Met à jour la valeur dans la base de données
    public function update(Request $request, AttributValeur $valeur)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'custom' => 'nullable|boolean',
        ]);

        $valeur->nom = $request->nom;
        $valeur->custom = $request->has('custom');

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($valeur->image) {
                Storage::disk('public')->delete($valeur->image);
            }
            $valeur->image = $request->file('image')->store('valeurs', 'public');
        }

        $valeur->save();

        return redirect()->route('attributs.show', $valeur->attribut_id)->with('success', 'Valeur mise à jour.');
    }

    // Supprime une valeur
    public function destroy(AttributValeur $valeur)
    {
        // Supprimer l'image si elle existe
        if ($valeur->image) {
            Storage::disk('public')->delete($valeur->image);
        }

        $valeur->delete();

        return redirect()->route('attributs.show', $valeur->attribut_id)->with('success', 'Valeur supprimée.');
    }
}
