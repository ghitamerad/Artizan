<?php

namespace App\Http\Controllers;

use App\Models\Mesure;
use App\Models\Modele;
use App\Http\Requests\StoremesureRequest;
use App\Http\Requests\UpdatemesureRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use SimpleXMLElement;


class MesureController extends Controller
{
    /**
     * Extraction des mesures depuis un fichier .vit
     */
    public function importMesuresFromVit(Modele $modele)
    {
        $filename = basename($modele->xml); // Récupérer le nom du fichier depuis la colonne `xml`


        // Vérifier si le fichier existe dans storage/public/mesures
        $path = storage_path("app/public/mesures/{$filename}");

        if (!file_exists($path)) {
            return back()->with('error', 'Fichier non trouvé.');
        }

        // Charger le fichier XML
        $xmlContent = file_get_contents($path);
        $xml = new SimpleXMLElement($xmlContent);

        // Parcourir les mesures et les ajouter à la base de données
        foreach ($xml->{"body-measurements"}->m as $measure) {
            $variableXml = (string) $measure['name'];
            $valeurParDefaut = (float) trim((string) $measure['value']); // Convertir en nombre


            // Vérifier si la mesure existe déjà
            Mesure::create([
                'modele_id' => $modele->id,
                'label' => str_replace('@', '', $variableXml), // Nettoyer le label
                'valeur_par_defaut' => $valeurParDefaut, // Récupérer la valeur du XML
                'variable_xml' => $variableXml
            ]);
        }

        return back()->with('success', 'Mesures importées avec succès.');
    }



    /**
     * Affichage du formulaire des mesures pour un modèle
     */
    public function showMesuresForm($modeleId)
    {
        $modele = \App\Models\Modele::findOrFail($modeleId);
        $mesures = Mesure::where('modele_id', $modeleId)->get();

        return view('mesures.form', compact('modele', 'mesures'));
    }

    /**
     * Store a newly created measure in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'modele_id' => 'required|exists:modeles,id',
            'label' => 'required|string|max:255',
            'valeur_par_defaut' => 'required|numeric',
            'variable_xml' => 'string|max:255|unique:mesures,variable_xml'
        ]);

        Mesure::create($request->all());

        return back()->with('success', 'Mesure ajoutée avec succès.');
    }

    /**
     * Show the form for editing the specified measure.
     */
    public function edit(Mesure $mesure)
    {
        return view('mesures.edit', compact('mesure'));
    }

    /**
     * Update the specified measure in storage.
     */

public function update(Request $request, Mesure $mesure)
{
    $request->validate([
        'label' => 'required|string|max:255',
        'valeur_par_defaut' => 'required|numeric',
        'variable_xml' => [
            'nullable',
            'string',
            'max:255',
            Rule::unique('mesures')->where(function ($query) use ($request, $mesure) {
                return $query->where('modele_id', $mesure->modele_id);
            })->ignore($mesure->id),
        ],
        'min' => 'required|numeric|lte:valeur_par_defaut',
        'max' => 'required|numeric|gte:valeur_par_defaut',
    ]);

    $mesure->update($request->all());

   return redirect()->back()->with('success', 'Mesure mise à jour.');

}


    /**
     * Remove the specified measure from storage.
     */
    public function destroy(Mesure $mesure)
    {
        $mesure->delete();

        return back()->with('success', 'Mesure supprimée.');
    }
}
