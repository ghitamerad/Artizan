<?php

namespace App\Http\Controllers;

use App\Models\Attribut;
use App\Models\Modele;
use App\Models\Categorie;
use App\Models\Devis;
use App\Models\Mesure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;
use App\Services\FusionPatronService;



class ModeleController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Modele::class);
        $filtre = $request->get('filtre');

        $modeles = Modele::query();

        if ($filtre === 'pretaporter') {
            $modeles->where('sur_commande', false);
        } elseif ($filtre === 'surmesure') {
            $modeles->where('sur_commande', true);
        } elseif ($filtre === 'rupture') {
            $modeles->where('stock', 0);
        }

        $modeles = $modeles->latest()->paginate(12); // ou selon ta pagination

        return view('modeles.index', compact('modeles', 'filtre'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Modele::class);

        $categories = Categorie::leaf()->get();
        $attributs = Attribut::with('valeurs')->get();
        $attributValeurs = collect();
        $cheminFichierVal = null;
        $cheminFichierMesure = null;

        if ($request->has('devis_id')) {
            $devi = Devis::with([
                'attributValeurs.attribut',
                'categorie'
            ])->findOrFail($request->devis_id);

            $attributValeurs = $devi->attributValeurs;

            // Récupérer uniquement les attributs obligatoires
            $valeursObligatoires = $attributValeurs->filter(function ($valeur) {
                return $valeur->attribut && $valeur->attribut->obligatoire;
            });

            // Injecter les données nécessaires dans la requête
            $request->merge([
                'categorie_id' => $devi->categorie_id,
                'attribut_valeurs' => $valeursObligatoires->pluck('id')->toArray(),
            ]);

            // Appeler le service de fusion
            $fusionService = new FusionPatronService();
            $fusionService->genererPatronPersonnalise($request, $devi->id);

            // Récupérer le chemin du fichier .val généré
            if ($devi->chemin_patron) {
                $cheminFichierVal = 'storage/' . $devi->chemin_patron;
            }

            // Retrouver la fiche de mesure utilisée pendant la génération
            $categorie = $devi->categorie;

            $categoriesAscendantes = collect([$categorie]);

            if ($categorie->parent) {
                $categoriesAscendantes->prepend($categorie->parent);
            }

            $categorieAvecFiche = $categoriesAscendantes->first(function ($c) {
                return $c->fichier_mesure;
            });

            if ($categorieAvecFiche && Storage::disk('public')->exists($categorieAvecFiche->fichier_mesure)) {
                $cheminFichierMesure = $categorieAvecFiche->fichier_mesure;
            }
        }

        return view('modeles.create', compact(
            'categories',
            'attributs',
            'attributValeurs',
            'cheminFichierVal',
            'cheminFichierMesure'
        ));
    }



    public function store(Request $request)
    {
        $this->authorize('create', Modele::class);

        // Validation des données entrantes
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'stock' => 'nullable|boolean',
            'sur_commande' => 'nullable|boolean',
            'patron' => 'nullable|file|max:2048',
            'type' => 'required|in:normal,fragment',
            'xml' => 'nullable|file|max:2048',
            'image' => 'nullable|image|max:2048',
            'attribut_valeurs' => 'nullable|array',
            'attribut_valeurs.*' => 'nullable|exists:attribut_valeurs,id',

        ]);

        // Création du modèle
        $modele = Modele::create([
            'nom' => $validatedData['nom'],
            'description' => $validatedData['description'] ?? null,
            'prix' => $validatedData['prix'],
            'categorie_id' => $validatedData['categorie_id'],
            'stock' => $request->has('stock'),
            'sur_commande' => $request->has('sur_commande'),
            'type' => $validatedData['type'],
        ]);

        if ($request->hasFile('image')) {
            $imageName = 'modele-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('modeles', $imageName, 'public');
            $modele->image = $path;
        }


        if ($request->filled('fichier_val_auto')) {
            $chemin = $request->input('fichier_val_auto'); // ex: 'storage/patrons_generes/xxx.val'
            $cheminRelatif = str_replace('storage/', '', $chemin); // devient 'patrons_generes/xxx.val'

            if (Storage::disk('public')->exists($cheminRelatif)) {
                $nomFinal = "patrons/modele-{$modele->id}-patron.val";
                Storage::disk('public')->put($nomFinal, Storage::disk('public')->get($cheminRelatif));

                $modele->patron = $nomFinal;
            }
        }

        if ($request->filled('fichier_mesure_auto')) {
            $chemin = $request->input('fichier_mesure_auto'); // ex: 'storage/mesures/...'
            $cheminRelatif = str_replace('storage/', '', $chemin); // ex: 'mesures/xxx.vit'

            if (Storage::disk('public')->exists($cheminRelatif)) {
                $nomFinal = "mesures/modele-{$modele->id}-mesures.vit";
                Storage::disk('public')->put($nomFinal, Storage::disk('public')->get($cheminRelatif));

                $modele->xml = $nomFinal;
            }
        }


        // Lier les valeurs d'attributs sélectionnées
        if (!empty($validatedData['attribut_valeurs'])) {
            $valeurIds = array_filter($validatedData['attribut_valeurs']); // enlève les champs vides
            $modele->attributValeurs()->sync($valeurIds);
        }

        // Gestion du fichier patron (.val)
        if ($request->hasFile('patron')) {
            $patronName = "modele-{$modele->id}-patron.val";
            $path = $request->file('patron')->storeAs('patrons', $patronName, 'public');
            $modele->patron = $path;
        }

        // Gestion du fichier XML (.vit/.xml)
        if ($request->hasFile('xml')) {
            $xmlName = "modele-{$modele->id}-mesures.vit";
            $path = $request->file('xml')->storeAs('mesures', $xmlName, 'public');
            $modele->xml = $path;
        }

        $modele->save();

        // Si un devis est lié à cette création, on met à jour le champ modele_id
if ($request->filled('devis_id')) {
    $devis = Devis::find($request->devis_id);
    if ($devis) {
        $devis->modele_id = $modele->id;
        $devis->save();
    }
}


        return redirect()->route('modeles.index')->with('message', 'Modèle ajouté avec succès !');
    }



    public function show(Modele $modele)
    {
        $this->authorize('view', $modele);
        $modele->load([
            'categorie',
            'mesures',
            'attributValeurs.attribut' // pour charger nom de l'attribut lié à chaque valeur
        ]);

        $mesures = $modele->mesures ?? collect();

        return view('modeles.show', compact('modele', 'mesures'));
    }

    public function edit(Modele $modele)
    {
        $categories = Categorie::leaf()->get();

        // On récupère les attributs avec leurs valeurs
        $attributs = Attribut::with('valeurs')->get();

        $modele->load('attributValeurs');


        // Les valeurs actuellement liées au modèle
        $selectedValeurs = $modele->attributValeurs()->pluck('attribut_valeur_id')->toArray();

        return view('modeles.edit', compact('modele', 'categories', 'attributs', 'selectedValeurs'));
    }



    public function update(Request $request, Modele $modele)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'attribut_valeurs' => 'array',
            'attribut_valeurs.*' => 'exists:attribut_valeurs,id',
            'patron' => 'nullable|file|max:2048',
            'xml' => 'nullable|file|max:2048',
            'image' => 'nullable|image|max:2048',
            'type' => 'required|in:normal,fragment',


        ]);

        $validatedData['stock'] = $request->has('stock') ? 1 : 0;
        $validatedData['sur_commande'] = $request->has('sur_commande') ? 1 : 0;

        if ($request->hasFile('image')) {
            if (!empty($modele->image) && Storage::disk('public')->exists($modele->image)) {
                Storage::disk('public')->delete($modele->image);
            }

            $imageName = 'modele-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('modeles', $imageName, 'public');
            $modele->image = $path;
        }


        // Fichiers
        // Fichiers
        if ($request->hasFile('patron')) {
            if (!empty($modele->patron) && Storage::disk('public')->exists($modele->patron)) {
                Storage::disk('public')->delete($modele->patron);
            }

            $patronName = "modele-{$modele->id}-patron.val";
            $modele->patron = $request->file('patron')->storeAs('patrons', $patronName, 'public');
        }

        if ($request->hasFile('xml')) {
            if (!empty($modele->xml) && Storage::disk('public')->exists($modele->xml)) {
                Storage::disk('public')->delete($modele->xml);
            }

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
            'patron' => $modele->patron,
            'xml' => $modele->xml,
            'type' => $validatedData['type'],

        ]);

        // On synchronise les valeurs d'attributs avec la table pivot
        $modele->attributValeurs()->sync(array_values($validatedData['attribut_valeurs'] ?? []));

        return redirect()->route('modeles.index')->with('message', 'Modèle mis à jour avec succès !');
    }

    public function destroy(Modele $modele)
    {
        $this->authorize('delete', $modele);

        if ($modele->image) {
            Storage::disk('public')->delete($modele->image);
        }

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
