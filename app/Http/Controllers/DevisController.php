<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Categorie;
use App\Models\Attribut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DevisController extends Controller
{
    public function genererPatron()
    {
//
    }

    public function index()
    {
        $devis = Devis::with('categorie')->latest()->get();
        return view('devis.index', compact('devis'));
    }

    public function create()
    {
        $categories = Categorie::leaf()->get();
        $attributs = Attribut::with('valeurs')->get();
        return view('devis.create', compact('categories', 'attributs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'categorie_id' => 'required|exists:categories,id',
            'attribut_valeurs' => 'nullable|array',
            'attribut_valeurs.*' => 'exists:attribut_valeurs,id',
        ]);

        $valeursSelectionnees = collect($request->input('attribut_valeurs', []));

        $attributsObligatoires = Attribut::with('valeurs')->where('obligatoire', true)->get();

        $erreurs = [];
        foreach ($attributsObligatoires as $attribut) {
            $idsValeurs = $attribut->valeurs->pluck('id');
            if ($valeursSelectionnees->intersect($idsValeurs)->isEmpty()) {
                $erreurs[] = "Vous devez sélectionner au moins une valeur pour l’attribut obligatoire « {$attribut->nom} ».";
            }
        }
        if (!empty($erreurs)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $erreurs
                ], 422);
            }

            return back()
                ->withInput()
                ->withErrors(['attribut_valeurs' => implode(' ', $erreurs)]);
        }


        $data = $request->only(['description', 'categorie_id']);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('devis_images', 'public');
        }

        $devis = \App\Models\Devis::create($data);
        $devis->attributValeurs()->sync($valeursSelectionnees);

        return redirect()->route('devis.index')->with('message', 'Devis créé avec succès');
    }


    public function show(Devis $devi)
    {
        $devi->load('categorie', 'attributValeurs.attribut');
        return view('devis.show', compact('devi'));
    }

    public function edit(Devis $devi)
    {
        $categories = Categorie::leaf()->get();
        $attributs = Attribut::with('valeurs')->get();
        $selectedValeurs = $devi->attributValeurs()->pluck('attribut_valeur_id')->toArray();

        return view('devis.edit', compact('devi', 'categories', 'attributs', 'selectedValeurs'));
    }

    public function update(Request $request, Devis $devi)
    {
        $data = $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'attribut_valeurs' => 'nullable|array',
            'attribut_valeurs.*' => 'exists:attribut_valeurs,id',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('devis', 'public');
        }

        $devi->update($data);

        // Récupère les IDs sélectionnés via les radios
        $selectedValeurs = array_values($data['attribut_valeurs'] ?? []);
        $devi->attributValeurs()->sync($selectedValeurs);

        return redirect()->route('devis.show', $devi)->with('success', 'Devis mis à jour.');
    }


    public function destroy(Devis $devi)
    {
        if ($devi->image) {
            Storage::disk('public')->delete($devi->image);
        }

        $devi->delete();
        return redirect()->route('devis.index')->with('message', 'Devis supprimé avec succès');
    }
}
