<?php

namespace App\Services;

use App\Models\DetailCommande;
use App\Models\MesureDetailCommande;
use Illuminate\Support\Facades\Log;

class GeneratePatronService{
    public function customPattern($detailCommandeId)
    {
        $detailCommande = DetailCommande::findOrFail($detailCommandeId);
        $modele = $detailCommande->modele;

        if (!$modele) {
            Log::error("Modèle introuvable pour la commande ID $detailCommandeId");
            return null;
        }

        // Chemins
        $originalXmlPath = storage_path("app/public/{$modele->xml}");
        $patronPath = storage_path("app/public/{$modele->patron}");
        $measurementDir = storage_path("app/public/client_measurements/");
        $customPatternDir = storage_path("app/public/custom_patterns/");

        if (!file_exists($measurementDir)) mkdir($measurementDir, 0777, true);
        if (!file_exists($customPatternDir)) mkdir($customPatternDir, 0777, true);

        $modifiedXmlPath = $measurementDir . "measurement_{$detailCommande->id}.xml";
        copy($originalXmlPath, $modifiedXmlPath);

        $xml = simplexml_load_file($modifiedXmlPath);
        if (!$xml) {
            Log::error("Erreur chargement XML pour commande $detailCommandeId");
            return null;
        }

        $mesures = MesureDetailCommande::where('details_commande_id', $detailCommande->id)->get();
        foreach ($mesures as $mesure) {
            $variableXml = $mesure->variable_xml;
            $valeurMesure = $mesure->valeur_mesure ?? $mesure->valeur_par_defauts;

            foreach ($xml->{"body-measurements"}->m as $m) {
                if ((string) $m['name'] === $variableXml) {
                    $m['value'] = $valeurMesure;
                    break;
                }
            }
        }

        $xml->asXML($modifiedXmlPath);

        $nomFichier = strtolower(str_replace(' ', '_', $modele->nom)) . "_{$detailCommande->id}";
        $outputFile = $customPatternDir . "{$nomFichier}.svg";

        $valentinaPath = 'C:\Program Files (x86)\Valentina';
        $command = "\"$valentinaPath\\valentina.exe\" -b \"$nomFichier\" -d \"$customPatternDir\" -f svg -m \"$modifiedXmlPath\" \"$patronPath\"";

        system($command);

        $generatedPattern = glob("{$customPatternDir}{$nomFichier}_1.svg");

        if (!empty($generatedPattern)) {
            $svgPath = "custom_patterns/" . basename($generatedPattern[0]);

            $detailCommande->fichier_patron = $svgPath;
            $detailCommande->custom = true;
            $detailCommande->save();

            Log::info("SVG généré avec succès : $svgPath");
            return $svgPath;
        } else {
            Log::error("SVG non généré pour $nomFichier");
            return null;
        }
    }
}
