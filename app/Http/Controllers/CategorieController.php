<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Http\Requests\StorecategorieRequest;
use App\Http\Requests\UpdatecategorieRequest;
use Illuminate\Http\Request;

class CategorieController extends Controller
{

    private function isCircular($categorie, $newParentId)
{
    if (is_null($newParentId)) return false;

    $parent = \App\Models\Categorie::find($newParentId);

    while ($parent) {
        if ($parent->id === $categorie->id) {
            return true; // boucle détectée
        }
        $parent = $parent->parent; // en supposant que la relation "parent" est définie dans le modèle
    }

    return false;
}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categorie::with('parent')->get();
        return view('categories.index', compact('categories'));


    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $categories = Categorie::all();
        return view('categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'categorie_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'fichier_mesure' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $data = $request->only(['nom', 'categorie_id']);


        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images/categories', 'public');
        }

        if ($request->hasFile('fichier_mesure')) {
            $data['fichier_mesure'] = $request->file('fichier_mesure')->store('mesures/categories', 'public');
        }

        Categorie::create($data);

        return redirect()->back()->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categorie $categorie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categorie $categorie)
    {
        $categories = \App\Models\Categorie::where('id', '!=', $categorie->id)->get(); // Pour le select de la catégorie parente
        return view('categories.edit', compact('categorie', 'categories'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categorie $categorie)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'categorie_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'fichier_mesure' => 'nullable|file|max:2048',
        ]);

            // Vérifie si on essaie de créer une association circulaire
    if ($this->isCircular($categorie, $request->categorie_id)) {
        return redirect()->back()->withErrors(['categorie_id' => 'Association circulaire détectée.'])->withInput();
    }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('images', 'public');
        }

        if ($request->hasFile('fichier_mesure')) {
            $validated['fichier_mesure'] = $request->file('fichier_mesure')->store('mesures', 'public');
        }

        $categorie->update($validated);

        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorie $categorie)
    {
        // Assurez-vous que la catégorie existe avant de la supprimer
        if ($categorie) {
            $categorie->delete();
            return redirect()->route('categories.index')->with('success', 'Catégorie supprimée avec succès.');
        }

        return redirect()->route('categories.index')->with('error', 'La catégorie n\'existe pas.');
    }


}
