<?php

namespace App\Http\Controllers;

use App\Models\Attribut;
use Illuminate\Http\Request;

class AttributController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Attribut::class); // Si tu utilises des policies
        $attributs = Attribut::latest()->get(); // ⬅️ Cette ligne trie du plus récent au plus ancien
        return view('attributs.index', compact('attributs'));
    }
    


    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        Attribut::create(['nom' => $request->nom]);

        return redirect()->route('attributs.index')->with('message', 'Attribut créé avec succès.');
    }

    public function update(Request $request, Attribut $attribut)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $attribut->update(['nom' => $request->nom]);

        return redirect()->route('attributs.index')->with('message', 'Attribut modifié avec succès.');
    }

    public function destroy(Attribut $attribut)
    {
        $attribut->delete();
        return redirect()->route('attributs.index')->with('message', 'Attribut supprimé avec succès.');
    }
}
