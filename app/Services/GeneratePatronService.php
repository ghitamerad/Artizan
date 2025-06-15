<?php

namespace App\Services;

use App\Models\DetailCommande;
use App\Models\MesureDetailCommande;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;


class GeneratePatronService
{
    public function generatePattern($detailCommandeId): ?string
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

        // Mise à jour des mesures dans le XML
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

        // Génération du fichier SVG
        $nomFichier = strtolower(str_replace(' ', '_', $modele->nom)) . "_{$detailCommande->id}";
        $outputFile = $customPatternDir . "{$nomFichier}.pdf";

        $valentinaPath = config('services.valentina.exe_path');

        if (!$valentinaPath || !file_exists($valentinaPath)) {
            Log::error("Le chemin vers l'exécutable Valentina est invalide ou non défini.");
            return back()->withErrors(['Le chemin vers l\'exécutable Valentina est invalide ou non défini.']);
        }
        $command = "\"$valentinaPath\\valentina.exe\" -b \"$nomFichier\" -d \"$customPatternDir\" -f 1 -p 0 -m \"$modifiedXmlPath\" \"$patronPath\"";

        $output = null;
        $return_var = null;
        exec($command, $output, $return_var);

        $generatedPattern = glob("{$customPatternDir}{$nomFichier}_1.pdf");

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

    public function downloadPattern($detailCommandeId)
    {
        $detailCommande = DetailCommande::findOrFail($detailCommandeId);

        if (!$detailCommande->fichier_patron) {
            Log::warning("Aucun fichier patron associé à la commande ID $detailCommandeId");
            return response()->json(['error' => 'Fichier patron non disponible.'], 404);
        }

        $path = storage_path("app/public/{$detailCommande->fichier_patron}");

        if (!file_exists($path)) {
            Log::error("Fichier patron manquant sur le disque pour la commande $detailCommandeId");
            return response()->json(['error' => 'Fichier introuvable.'], 404);
        }

        return Response::download($path);
    }

    // public function customPattern($detailCommandeId)
    // {
    //     $detailCommande = DetailCommande::findOrFail($detailCommandeId);
    //     $modele = $detailCommande->modele;

    //     if (!$modele) {
    //         Log::error("Modèle introuvable pour la commande ID $detailCommandeId");
    //         return null;
    //     }

    //     // Chemins
    //     $originalXmlPath = storage_path("app/public/{$modele->xml}");
    //     $patronPath = storage_path("app/public/{$modele->patron}");
    //     $measurementDir = storage_path("app/public/client_measurements/");
    //     $customPatternDir = storage_path("app/public/custom_patterns/");

    //     if (!file_exists($measurementDir)) mkdir($measurementDir, 0777, true);
    //     if (!file_exists($customPatternDir)) mkdir($customPatternDir, 0777, true);

    //     $modifiedXmlPath = $measurementDir . "measurement_{$detailCommande->id}.xml";
    //     copy($originalXmlPath, $modifiedXmlPath);

    //     $xml = simplexml_load_file($modifiedXmlPath);
    //     if (!$xml) {
    //         Log::error("Erreur chargement XML pour commande $detailCommandeId");
    //         return null;
    //     }

    //     $mesures = MesureDetailCommande::where('details_commande_id', $detailCommande->id)->get();
    //     foreach ($mesures as $mesure) {
    //         $variableXml = $mesure->variable_xml;
    //         $valeurMesure = $mesure->valeur_mesure ?? $mesure->valeur_par_defauts;

    //         foreach ($xml->{"body-measurements"}->m as $m) {
    //             if ((string) $m['name'] === $variableXml) {
    //                 $m['value'] = $valeurMesure;
    //                 break;
    //             }
    //         }
    //     }

    //     $xml->asXML($modifiedXmlPath);

    //     $nomFichier = strtolower(str_replace(' ', '_', $modele->nom)) . "_{$detailCommande->id}";
    //     $outputFile = $customPatternDir . "{$nomFichier}.svg";

    //$valentinaPath = config('services.valentina.exe_path');

    // if (!$valentinaPath || !file_exists($valentinaPath)) {
    //     Log::error("Le chemin vers l'exécutable Valentina est invalide ou non défini.");
    //     return back()->withErrors(['Le chemin vers l\'exécutable Valentina est invalide ou non défini.']);
    // }
    //     $command = "\"$valentinaPath\\valentina.exe\" -b \"$nomFichier\" -d \"$customPatternDir\" -f svg -m \"$modifiedXmlPath\" \"$patronPath\"";

    //     system($command);

    //     $generatedPattern = glob("{$customPatternDir}{$nomFichier}_1.svg");

    //     if (!empty($generatedPattern)) {
    //         $svgPath = "custom_patterns/" . basename($generatedPattern[0]);

    //         $detailCommande->fichier_patron = $svgPath;
    //         $detailCommande->custom = true;
    //         $detailCommande->save();

    //         Log::info("SVG généré avec succès : $svgPath");
    //         return $svgPath;
    //     } else {
    //         Log::error("SVG non généré pour $nomFichier");
    //         return null;
    //     }
    // }
}
