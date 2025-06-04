<?php

namespace App\Http\Controllers;

use App\Models\AttributValeur;
use App\Models\Categorie;
use App\Models\ElementPatron;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DOMDocument;
use Illuminate\Support\Facades\Log;

use DOMXPath;
use SimpleXMLElement;
use Illuminate\Support\Facades\Storage;

class ElementPatronController extends Controller
{
    public function index()
    {
        $elements = ElementPatron::with(['categorie', 'attributValeur'])->get();
        return view('element_patrons.index', compact('elements'));
    }

    public function create()
    {
        $categories = Categorie::leaf()->get();
        $valeurs = AttributValeur::all();
        return view('element_patrons.create', compact('categories', 'valeurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fichier_patron' => 'required|file',
            'categorie_id' => 'required|exists:categories,id',
            'attribut_valeur_id' => 'required|exists:attribut_valeurs,id',
        ]);

        $file = $request->file('fichier_patron');
        $valeur = AttributValeur::findOrFail($request->attribut_valeur_id);
        $categorie = Categorie::findOrFail($request->categorie_id);

        // Générer le nom du fichier : categorie_nom + attribut_valeur_nom
        $uniqueSuffix = uniqid(); // ou time() ou Str::random(6)
        $filename = Str::slug($categorie->nom . '_' . $valeur->nom) . '_' . $uniqueSuffix . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('element-patrons', $filename, 'public');

        ElementPatron::create([
            'fichier_patron' => $path,
            'categorie_id' => $request->categorie_id,
            'attribut_valeur_id' => $request->attribut_valeur_id,
        ]);

        return redirect()->route('element-patrons.index')->with('success', 'Élément ajouté avec succès.');
    }


    public function show(ElementPatron $elementPatron)
    {
        return view('element_patrons.show', compact('elementPatron'));
    }


    public function edit(ElementPatron $elementPatron)
    {
        $categories = Categorie::leaf()->get();
        $valeurs = AttributValeur::all();
        return view('element_patrons.edit', compact('elementPatron', 'categories', 'valeurs'));
    }

    public function update(Request $request, ElementPatron $elementPatron)
    {
        $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'attribut_valeur_id' => 'required|exists:attribut_valeurs,id',
            'fichier_patron' => 'nullable|file',
        ]);

        $elementPatron->categorie_id = $request->categorie_id;
        $elementPatron->attribut_valeur_id = $request->attribut_valeur_id;

        if ($request->hasFile('fichier_patron')) {
            $file = $request->file('fichier_patron');
            $valeur = AttributValeur::findOrFail($request->attribut_valeur_id);
            $categorie = Categorie::findOrFail($request->categorie_id);

            $uniqueSuffix = uniqid(); // ou time() ou Str::random(6)
            $filename = Str::slug($categorie->nom . '_' . $valeur->nom) . '_' . $uniqueSuffix . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('element-patrons', $filename, 'public');

            $elementPatron->fichier_patron = $path;
        }

        $elementPatron->save();

        return redirect()->route('element-patrons.index')->with('success', 'Élément modifié avec succès.');
    }


    public function destroy(ElementPatron $elementPatron)
    {
        // Supprimer le fichier du disque s'il existe
        if ($elementPatron->fichier_patron && Storage::disk('public')->exists($elementPatron->fichier_patron)) {
            Storage::disk('public')->delete($elementPatron->fichier_patron);
        }

        // Supprimer l'enregistrement de la base de données
        $elementPatron->delete();

        return redirect()->route('element-patrons.index')->with('success', 'Élément supprimé.');
    }

    public function genererPatronPersonnalise(Request $request)
    {
        Log::info('Début de genererPatronPersonnalise');

        $valentinaExePath = 'C:\\Program Files (x86)\\Valentina\\valentina.exe'; // ← Chemin à ajuster si besoin

        $categorieId = $request->input('categorie_id');
        $valeursIds = collect($request->input('attribut_valeurs'))->values();

        Log::info("categorieId: $categorieId, valeursIds: " . $valeursIds->implode(','));
        $valeurs = \App\Models\AttributValeur::whereIn('id', $valeursIds)
            ->with('elementsPatron')
            ->get();

        // 1. Trouver les fichiers .val correspondant aux attributs sélectionnés
        Log::info('Début récupération fichiers...');
        $fichiers = $valeurs->flatMap(function ($valeur) {
            Log::info("AttributValeur id={$valeur->id} - éléments patron: " . $valeur->elementsPatron->count());
            foreach ($valeur->elementsPatron as $ep) {
                Log::info(" - fichier patron: " . $ep->fichier_patron);
            }
            return $valeur->elementsPatron->map(function ($element) {
                return storage_path('app/public/' . $element->fichier_patron);
            });
        })->filter();

        Log::info('Fichiers récupérés : ' . $fichiers->implode(', '));

        if ($fichiers->isEmpty()) {
            Log::error('Aucun fichier de patron trouvé pour les attributs sélectionnés.');
            // gestion d'erreur
        }
        // 2. Trouver la fiche de mesure associée à une catégorie parente
        $categorie = \App\Models\Categorie::with('parent')->findOrFail($categorieId);
        Log::info("Catégorie trouvée: {$categorie->nom}");

        // Préparer la collection avec la catégorie elle-même et son parent (s'il existe)
        $categories = collect([$categorie]);

        if ($categorie->parent) {
            $categories->prepend($categorie->parent);
        }

        // Chercher la première catégorie avec une fiche de mesure existante
        $categorieAvecFiche = $categories->first(function ($c) {
            return $c->fichier_mesure;
        });

        if (!$categorieAvecFiche) {
            Log::error("Aucune catégorie avec fiche de mesure trouvée.");
            return back()->with('error', "Aucune fiche de mesure trouvée pour cette catégorie.");
        }

        Log::info("Catégorie utilisée pour la fiche de mesure : " . $categorieAvecFiche->nom);

        Log::info("Catégorie avec fiche de mesure: {$categorieAvecFiche->nom}");
        Log::info("Chemin fiche mesure: {$categorieAvecFiche->fichier_mesure}");

        if (!Storage::disk('public')->exists($categorieAvecFiche->fichier_mesure)) {
            Log::error("Le fichier de mesure indiqué n’existe pas sur le disque.");
            throw new \Exception("Le fichier de mesure indiqué n’existe pas sur le disque.");
        }

        $measureFile = storage_path('app/public/' . $categorieAvecFiche->fichier_mesure);
        Log::info("Chemin fiche mesure: $measureFile");
        $outputFolder = storage_path('app/public/patrons_generes/' . now()->format('Ymd_His'));
        if (!file_exists($outputFolder)) {
            mkdir($outputFolder, 0777, true);
            Log::info("Dossier de sortie créé : $outputFolder");
        }

        // 3. Fusionner les fichiers
        try {
            $this->fusionnerValentinaFiles($fichiers->toArray(), $outputFolder, $valentinaExePath, $measureFile);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la fusion des fichiers Valentina: ' . $e->getMessage());
            return back()->withErrors(['Erreur lors de la génération du patron personnalisé.']);
        }

        Log::info('Fusion des fichiers terminée, fichier PDF généré.');

$pdfName = 'patron_' . Str::slug($categorieAvecFiche->nom) . '_' . now()->format('Ymd_His') . '.pdf';


return response()->download($outputFolder . '/' . $pdfName);
    }


    private function fusionnerValentinaFiles(array $files, string $outputFolder, string $valentinaExePath, string $measureFile)
    {
        Log::info('Début de fusionnerValentinaFiles');
        Log::info('Fichiers à fusionner: ' . implode(', ', $files));
        Log::info("Chemin Valentina: $valentinaExePath");
        Log::info("Fichier mesure: $measureFile");
        Log::info("Dossier sortie: $outputFolder");

        $attributesToPrefix = [
            'id',
            'basePoint',
            'secondPoint',
            'firstPoint',
            'point1',
            'point2',
            'point3',
            'point4',
            'center',
            'curve',
            'curve1',
            'curve2',
            'pSpline',
            'secondArc',
            'firstArc',
            'p1Line1',
            'p1Line2',
            'p2Line1',
            'p2Line2',
            'idObject'
        ];

        $prefixXmlAttributes = function (&$element, $attributesToPrefix, $prefix) use (&$prefixXmlAttributes) {
            foreach ($element->attributes() as $attr => $value) {
                if (in_array($attr, $attributesToPrefix) && is_numeric((string)$value)) {
                    $element[$attr] = $prefix . $value;
                }
            }
            if ($element->getName() === 'record' && isset($element['path'])) {
                $paths = explode(' ', (string)$element['path']);
                $newPaths = array_map(fn($id) => is_numeric($id) ? $prefix . $id : $id, $paths);
                $element['path'] = implode(' ', $newPaths);
            }
            foreach ($element->children() as $child) {
                $prefixXmlAttributes($child, $attributesToPrefix, $prefix);
            }
        };

        // Charger tous les XML
        $xmlObjects = [];
        foreach ($files as $index => $file) {
            Log::info("Chargement du fichier XML : $file");
            $xml = simplexml_load_file($file);
            if (!$xml) {
                throw new \Exception("Impossible de charger le fichier XML: $file");
            }
            $prefix = (string)(($index + 1) * 100);
            $prefixXmlAttributes($xml, $attributesToPrefix, $prefix);
            $xmlObjects[] = $xml;
        }

        // Utiliser le dernier comme base
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $lastFile = end($files);
        Log::info("Chargement du dernier fichier comme base: $lastFile");
        if (!$dom->load($lastFile)) {
            throw new \Exception("Impossible de charger le fichier DOM: $lastFile");
        }

        $xpath = new DOMXPath($dom);
        foreach ($xpath->query('//draw') as $drawNode) {
            $drawNode->parentNode->removeChild($drawNode);
        }

        $patternNode = $xpath->query('//pattern')->item(0);
        if (!$patternNode) {
            throw new \Exception('No <pattern> node found in the XML.');
        }

        $existingNames = [];

        foreach ($xmlObjects as $xml) {
            foreach ($xml->draw as $draw) {
                $name = (string)$draw['name'];
                $original = $name;
                $suffix = 1;
                while (in_array($name, $existingNames)) {
                    $name = $original . '_' . $suffix++;
                }
                $draw['name'] = $name;
                $existingNames[] = $name;

                $imported = $dom->importNode(dom_import_simplexml($draw), true);
                $patternNode->appendChild($imported);
            }
        }
        // Injecter le chemin absolu du fichier de mesures dans la balise <measurements>
        $measureAbsolutePath = $measureFile; // ex: C:\xampp2\htdocs\Artizan\storage\app\public\mesures\mesure1.vit
        $measureNodes = $xpath->query('//measurements');
        if ($measureNodes->length > 0) {
            $measureNodes->item(0)->nodeValue = $measureAbsolutePath;
        } else {
            $newMeasurementNode = $dom->createElement('measurements', $measureAbsolutePath);
            $patternNode->insertBefore($newMeasurementNode, $patternNode->firstChild);
        }
        Log::info("Chemin de mesure injecté dans le .val : $measureAbsolutePath");


        // Sauvegarde du .val fusionné
        $timestamp = date('Ymd_His');
        $outputVal = $outputFolder . "/fusionne_{$timestamp}.val";
        $dom->save($outputVal);
        Log::info("Fichier .val fusionné sauvegardé: $outputVal");

        // Génération PDF via Valentina
        $patternName = pathinfo($outputVal, PATHINFO_FILENAME);
        $cmd = "\"$valentinaExePath\" -b $patternName -d \"$outputFolder\" --exportOnlyDetails -f 1 -p 0 -m \"$measureAbsolutePath\" \"$outputVal\"";
        Log::info("Commande Valentina: $cmd");

        system($cmd, $exitCode);
        Log::info("Code retour commande Valentina: $exitCode");

        if ($exitCode !== 0) {
            throw new \Exception("Erreur lors de l’export PDF Valentina. Code : $exitCode");
        }
    }
}
