<?php
namespace App\Http\Controllers;

use App\Models\Modele;
use App\Models\Categorie;
use App\Models\mesure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;


class ModeleController extends Controller
{

    public function extractMesures(Modele $modele)
{
    if (!$modele->xml || !Storage::exists('public/' . $modele->xml)) {
        return back()->with('error', 'Fichier XML introuvable.');
    }

    // Charger le fichier XML
    $xmlContent = Storage::get('public/' . $modele->xml);
    $xml = new SimpleXMLElement($xmlContent);

    // Supposons que les mesures soient stockées sous <mesures><mesure nom="Taille" valeur="100"/>
    foreach ($xml->mesures->mesure as $m) {
        Mesure::updateOrCreate(
            ['modele_id' => $modele->id, 'label' => (string) $m['nom']],
            ['valeur_par_defaut' => (float) $m['valeur'], 'variable_xml' => (string) $m['nom']]
        );
    }

    return back()->with('success', 'Mesures extraites avec succès.');
}
    /**
     * Affiche la liste des modèles.
     */
    public function index()
    {
        $this->authorize('viewAny', Modele::class);
        $modeles = Modele::all();
        return view('modeles.index', compact('modeles'));
    }

    /**
     * Affiche le formulaire de création d'un modèle.
     */
    public function create()
    {
        $this->authorize('create', Modele::class);
        $categories = Categorie::all(); // Récupère toutes les catégories
        return view('modeles.create', compact('categories'));
    }

    /**
     * Enregistre un modèle en base de données.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Modele::class);

        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'patron' => 'required|file|max:2048', // Fichier .val
            'xml' => 'required|file|max:2048', // Fichier .vit ou .xml
        ]);

        // Création du modèle sans fichiers
        $modele = Modele::create([
            'nom' => $validatedData['nom'],
            'description' => $validatedData['description'],
            'prix' => $validatedData['prix'],
            'categorie_id' => $validatedData['categorie_id'],
        ]);

        // Stockage des fichiers avec des noms clairs
        $patronName = "modele-{$modele->id}-patron.val";
        $patronPath = $request->file('patron')->storeAs('patrons', $patronName, 'public');

        $xmlName = "modele-{$modele->id}-mesures.vit";
        $xmlPath = $request->file('xml')->storeAs('mesures', $xmlName, 'public');

        // Mise à jour du modèle avec les chemins des fichiers
        $modele->update([
            'patron' => $patronPath,
            'xml' => $xmlPath,
        ]);

        return redirect()->route('modeles.index')->with('message', 'Modèle ajouté avec succès !');
    }


    /**
     * Affiche les détails d'un modèle.
     */
    public function show(Modele $modele)
    {
        $this->authorize('view', $modele);
        $mesures = $modele->mesures ?? collect(); // Assurez-vous que cette relation existe

        return view('modeles.show', compact('modele', 'mesures'));
    }

    /**
     * Affiche le formulaire d'édition d'un modèle.
     */
    public function edit(Modele $modele)
    {
        $this->authorize('update', $modele);
        $categories = Categorie::all();
        return view('modeles.edit', compact('modele', 'categories'));
    }

    /**
     * Met à jour un modèle en base de données.
     */
    public function update(Request $request, Modele $modele)
    {
        $this->authorize('update', $modele);

        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'patron' => 'nullable|file|max:2048', // Fichier .val
            'xml' => 'nullable|file|max:2048', // Fichier .vit ou .xml
        ]);

        // Mise à jour des fichiers si fournis
        if ($request->hasFile('patron')) {
            Storage::disk('public')->delete($modele->patron); // Supprime l'ancien fichier
            $patronName = "modele-{$modele->id}-patron.val";
            $modele->patron = $request->file('patron')->storeAs('patrons', $patronName, 'public');
        }

        if ($request->hasFile('xml')) {
            Storage::disk('public')->delete($modele->xml);
            $xmlName = "modele-{$modele->id}-mesures.vit";
            $modele->xml = $request->file('xml')->storeAs('mesures', $xmlName, 'public');
        }

        // Mise à jour des autres données
        $modele->update([
            'nom' => $validatedData['nom'],
            'description' => $validatedData['description'],
            'prix' => $validatedData['prix'],
            'categorie_id' => $validatedData['categorie_id'],
        ]);

        return redirect()->route('modeles.index')->with('message', 'Modèle mis à jour avec succès !');
    }


    /**
     * Supprime un modèle.
     */
    public function destroy(Modele $modele)
    {
        $this->authorize('delete', $modele);

        // Supprimer les fichiers associés
        if ($modele->patron) {
            Storage::disk('public')->delete($modele->patron);
        }
        if ($modele->xml) {
            Storage::disk('public')->delete($modele->xml);
        }

        // Suppression du modèle
        $modele->delete();

        return redirect()->route('modeles.index')->with('message', 'Modèle supprimé avec succès.');
    }

}

