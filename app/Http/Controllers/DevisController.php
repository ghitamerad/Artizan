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
        $data = $request->validate([
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'categorie_id' => 'required|exists:categories,id',
            'attribut_valeurs' => 'nullable|array',
            'attribut_valeurs.*' => 'exists:attribut_valeurs,id',
        ]);

        $data['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('devis_images', 'public');
        }

        $devis = Devis::create($data);

        if (!empty($data['attribut_valeurs'])) {
            $devis->attributValeurs()->sync($data['attribut_valeurs']);
        }

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
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'categorie_id' => 'required|exists:categories,id',
            'attribut_valeurs' => 'nullable|array',
            'attribut_valeurs.*' => 'exists:attribut_valeurs,id',
        ]);

        if ($request->hasFile('image')) {
            if ($devi->image) {
                Storage::disk('public')->delete($devi->image);
            }
            $data['image'] = $request->file('image')->store('devis_images', 'public');
        }

        $devi->update($data);
        $devi->attributValeurs()->sync($data['attribut_valeurs'] ?? []);

        return redirect()->route('devis.index')->with('message', 'Devis mis à jour avec succès');
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
