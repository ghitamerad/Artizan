<?php

namespace App\Http\Controllers;

use App\Models\mesure;
use App\Http\Requests\StoremesureRequest;
use App\Http\Requests\UpdatemesureRequest;

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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoremesureRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(mesure $mesure)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(mesure $mesure)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatemesureRequest $request, mesure $mesure)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(mesure $mesure)
    {
        //
    }
}
