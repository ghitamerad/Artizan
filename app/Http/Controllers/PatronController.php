<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\modele;
use App\Models\DetailCommande;
use App\Models\MesureDetailCommande;
use Illuminate\Support\Facades\Storage;

class PatronController extends Controller
{
    public function generatePatron($modeleId)
    {
        $modele = Modele::findOrFail($modeleId);

        // Définir les chemins des fichiers
        $patronPath = storage_path("app/public/{$modele->patron}");
        $xmlPath = storage_path("app/public/{$modele->xml}");
        $outputDir = storage_path("app/public/generated_patterns/");

        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true); // Créer le dossier s'il n'existe pas
        }

        $outputFile = $outputDir . "generated_{$modele->id}.val"; // Nom du fichier généré

        // Exécuter la commande Valentina
        $valentinaPath = 'C:\Program Files (x86)\Valentina';
        $nomFichier = str_replace(' ', '_', strtolower($modele->nom)); // Remplace les espaces par des underscores
        $command = "\"$valentinaPath\\valentina.exe\" -b \"$nomFichier\" -d \"$outputDir\" -f 0 -m \"$xmlPath\" \"$patronPath\"";

        system($command);

        // Vérifier si le fichier a été généré
        if (file_exists($outputFile)) {
            return response()->download($outputFile)->deleteFileAfterSend();
        } else {
            return response()->json(['error' => 'Le fichier n\'a pas été généré'], 500);
        }
    }








    public function customPattern($detailCommandeId)
    {
        $detailCommande = DetailCommande::findOrFail($detailCommandeId);
        $modele = $detailCommande->modele;

        if (!$modele) {
            return response()->json(['error' => 'Modèle introuvable pour cette commande'], 404);
        }

        // Définition des chemins
        $originalXmlPath = storage_path("app/public/{$modele->xml}");
        $patronPath = storage_path("app/public/{$modele->patron}");

        // Définition des nouveaux répertoires
        $measurementDir = storage_path("app/public/client_measurements/");
        $customPatternDir = storage_path("app/public/custom_patterns/");

        // Vérifier et créer les répertoires si nécessaire
        if (!file_exists($measurementDir)) {
            mkdir($measurementDir, 0777, true);
        }
        if (!file_exists($customPatternDir)) {
            mkdir($customPatternDir, 0777, true);
        }

        // Création du fichier XML client dans client_measurements/
        $modifiedXmlPath = $measurementDir . "measurement_{$detailCommande->id}.xml";
        copy($originalXmlPath, $modifiedXmlPath);

        // Chargement du fichier XML
        $xml = simplexml_load_file($modifiedXmlPath);

        if (!$xml) {
            return response()->json(['error' => 'Erreur lors du chargement du fichier XML'], 500);
        }

        // Modifier les valeurs en fonction des mesures enregistrées
        $mesures = MesureDetailCommande::where('details_commande_id', $detailCommande->id)->get();

        foreach ($mesures as $mesure) {
            $variableXml = $mesure->variable_xml;
            $valeurMesure = $mesure->valeur_mesure ?? $mesure->valeur_par_defauts;

            // Chercher et modifier la bonne balise <m name="...">
            foreach ($xml->{"body-measurements"}->m as $m) {
                if ((string) $m['name'] === $variableXml) {
                    $m['value'] = $valeurMesure;
                    break;
                }
            }
        }

        // Sauvegarder les modifications dans le fichier XML
        $xml->asXML($modifiedXmlPath);

        // Définition du fichier de sortie dans custom_patterns/
        $outputFile = $customPatternDir . "custom_patron_{$detailCommande->id}.val";

        // Exécuter Valentina avec le fichier XML client
        $valentinaPath = 'C:\Program Files (x86)\Valentina';
        $nomFichier = str_replace(' ', '_', strtolower($modele->nom));
        $command = "\"$valentinaPath\\valentina.exe\" -b \"$nomFichier\" -d \"$customPatternDir\" -f 0 -m \"$modifiedXmlPath\" \"$patronPath\"";

        system($command);

        // Vérifier si le patron personnalisé a bien été généré
        if (file_exists($outputFile)) {
            // Mise à jour du chemin du patron généré dans la base de données
            $detailCommande->update([
                'fichier_patron' => "custom_patterns/custom_patron_{$detailCommande->id}.val",
                'custom' => true,
            ]);

            return response()->download($outputFile)->deleteFileAfterSend();
        } else {
            return response()->json(['error' => 'Le fichier patron n\'a pas été généré'], 500);
        }
    }




    /**
     * Affiche le patron standard d'un modèle.
     *
     * @param int $modeleId
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function showPatron($modeleId)
    {
        $modele = Modele::findOrFail($modeleId);

        // Définir le chemin du fichier SVG généré
        $svgPath = asset("storage/generated_patterns/{$modele->nom}_1.svg");

        return view('patron.show', compact('svgPath'));
    }

    /**
     * Affiche le patron personnalisé généré pour un client.
     *
     * @param int $detailCommandeId
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function showCustomPattern($detailCommandeId)
    {
        // Récupérer la commande en détail
        $detailCommande = DetailCommande::findOrFail($detailCommandeId);

        // Vérifier si un patron personnalisé a été généré
        if (!$detailCommande->fichier_patron) {
            return response()->json(['error' => 'Aucun patron personnalisé trouvé pour cette commande.'], 404);
        }

        // Définir le chemin du patron personnalisé
        $customPatronPath = asset("storage/{$detailCommande->fichier_patron}");

        return view('patron.custom_show', compact('customPatronPath'));
    }

}
