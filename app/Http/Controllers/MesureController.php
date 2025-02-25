<?php

namespace App\Http\Controllers;

use App\Models\mesure;
use App\Models\modele;
use App\Http\Requests\StoremesureRequest;
use App\Http\Requests\UpdatemesureRequest;
use Illuminate\Http\Request;

class MesureController extends Controller
{

     /**
     * Extraction des mesures depuis un fichier .vit
     */
    private function extraireMesuresVit($xmlContent)
    {
        $xml = simplexml_load_string($xmlContent);
        $mesures = [];

        foreach ($xml->{'body-measurements'}->m as $mesure) {
            $nom = (string) $mesure['name'];
            $valeur = (float) $mesure['value'];
            $mesures[] = ['nom' => $nom, 'valeur' => $valeur];
        }

        return $mesures;
    }

        /**
     * Affichage du formulaire des mesures pour un modèle
     */
    public function showMesuresForm($modeleId)
    {
        $modele = \App\Models\modele::findOrFail($modeleId);
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
            'variable_xml' => 'string|max:255|unique:mesures,variable_xml,' . $mesure->id
        ]);

        $mesure->update($request->all());

        return redirect()->route('modeles.edit', $mesure->modele_id)->with('success', 'Mesure mise à jour.');
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
