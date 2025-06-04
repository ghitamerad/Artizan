<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modele;
use App\Models\DetailCommande;
use App\Models\MesureDetailCommande;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;


class PatronController extends Controller
{

    public function telecharger($id)
    {
        Log::info("lilou");
        $detailCommande = DetailCommande::findOrFail($id);

        if (!$detailCommande->fichier_patron) {
            return redirect()->back()->with('error', 'Aucun fichier trouvé.');
        }

        $path = storage_path("app/public/" . $detailCommande->fichier_patron);

        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'Le fichier n\'existe pas sur le serveur.');
        }

        return response()->download($path);
    }


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
            Log::error("Modèle introuvable pour la commande ID $detailCommandeId");
            return redirect()->back()->with('error', 'Modèle introuvable pour cette commande.');
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
            return redirect()->back()->with('error', 'Erreur lors du chargement du fichier de mesures.');
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

        // Génération du fichier PDF
        $nomFichier = strtolower(str_replace(' ', '_', $modele->nom)) . "_{$detailCommande->id}";
        $outputFile = $customPatternDir . "{$nomFichier}.pdf";

        $valentinaPath = 'C:\Program Files (x86)\Valentina';

        $type = $modele->type; // 'normal' ou 'fragment'

        // Commande selon le type
        $extraOption = $type === 'fragment' ? '--exportOnlyDetails' : '';

        $command = "\"$valentinaPath\\valentina.exe\" -b \"$nomFichier\" -d \"$customPatternDir\" -f 1 -p 0 -m \"$modifiedXmlPath\" $extraOption \"$patronPath\"";


        $output = null;
        $return_var = null;
        exec($command, $output, $return_var);

        $generatedPattern = glob("{$customPatternDir}{$nomFichier}_1.pdf");

        if (!empty($generatedPattern)) {
            $pdfPath = "custom_patterns/" . basename($generatedPattern[0]);

            $detailCommande->fichier_patron = $pdfPath;
            $detailCommande->custom = true;
            $detailCommande->save();

            Log::info("PDF généré avec succès : $pdfPath");
            return redirect()->back()->with('success', 'Le patron PDF a été généré avec succès.');
        } else {
            Log::error("PDF non généré pour $nomFichier");
            return redirect()->back()->with('error', 'Échec de la génération du patron PDF.');
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
