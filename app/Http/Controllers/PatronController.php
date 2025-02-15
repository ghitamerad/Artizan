<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\modele;
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
        $command = "\"$valentinaPath\\valentina.exe\" -b SELMA -d \"$outputDir\" -f 0 -m \"$xmlPath\" \"$patronPath\"";
    
        system($command);
    
        // Vérifier si le fichier a été généré
        if (file_exists($outputFile)) {
            return response()->download($outputFile)->deleteFileAfterSend();
        } else {
            return response()->json(['error' => 'Le fichier n\'a pas été généré'], 500);
        }
    }
    
}
