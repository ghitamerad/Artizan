<?php
namespace App\Http\Controllers;

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
        $modeles = Modele::all();
        return view('modeles.index', compact('modeles'));
    }

    public function create()
    {
        $this->authorize('create', Modele::class);
        $categories = Categorie::all();
        return view('modeles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Modele::class);

        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'stock' => 'required|boolean',
            'sur_commande' => 'required|boolean',
            'patron' => 'nullable|file|max:2048',
            'xml' => 'nullable|file|max:2048',
        ]);

        $modele = Modele::create([
            'nom' => $validatedData['nom'],
            'description' => $validatedData['description'],
            'prix' => $validatedData['prix'],
            'categorie_id' => $validatedData['categorie_id'],
            'stock' => $validatedData['stock'],
            'sur_commande' => $validatedData['sur_commande'],
        ]);

        if ($request->hasFile('patron')) {
            $patronName = "modele-{$modele->id}-patron.val";
            $modele->patron = $request->file('patron')->storeAs('patrons', $patronName, 'public');
        }

        if ($request->hasFile('xml')) {
            $xmlName = "modele-{$modele->id}-mesures.vit";
            $modele->xml = $request->file('xml')->storeAs('mesures', $xmlName, 'public');
        }

        $modele->save();

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
        $this->authorize('update', $modele);
        $categories = Categorie::all();
        return view('modeles.edit', compact('modele', 'categories'));
    }

    public function update(Request $request, Modele $modele)
    {
        $this->authorize('update', $modele);

        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'stock' => 'required|boolean',
            'sur_commande' => 'required|boolean',
            'patron' => 'nullable|file|max:2048',
            'xml' => 'nullable|file|max:2048',
        ]);

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
