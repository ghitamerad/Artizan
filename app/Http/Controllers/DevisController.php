<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Categorie;
use App\Models\Attribut;
use App\Models\AttributValeur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\DevisProposeNotification;
use App\Notifications\DevisReponduParClientNotification;
use Illuminate\Support\Facades\Notification;

class DevisController extends Controller
{

public function indexClient()
{
    $devis = Devis::where('user_id', Auth::id())
        ->latest()
        ->get();

    return view('devis.mes-devis', [
        'devis' => $devis,
        'filtre' => 'tous' // juste à titre d’info si tu veux l’afficher dans la vue
    ]);
}
    public function repondre(Request $request, Devis $devi)
    {
        $request->validate([
            'tarif' => ['required', 'numeric', 'min:0'],
        ]);

        $devi->update(['tarif' => $request->tarif]);

        $client = $devi->utilisateur;

        // Notification in-app
        $client->notify(new DevisProposeNotification($devi));

        return redirect()->route('devis.show', $devi)
            ->with('success', 'Tarif proposé avec succès. Le client a été notifié.');
    }



    public function repondreClient(Request $request, Devis $devi)
    {
        $request->validate([
            'statut' => 'required|in:aceptee,refusee',
        ]);

        // Vérifie que l'utilisateur est bien le client concerné
        if ($devi->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        // Le devis doit avoir un tarif proposé et ne pas déjà avoir été répondu
        if (!$devi->tarif || $devi->statut !== "en_attente") {
            return redirect()->back()->with('error', 'Action impossible.');
        }

        $devi->update([
            'statut' => $request->statut,
        ]);

        $gerantes = User::where('role', 'gerante')->get();
        Notification::send($gerantes, new DevisReponduParClientNotification($devi));


        return redirect()->route('devis.client.show', $devi)->with('success', 'Réponse enregistrée avec succès.');
    }



    public function formGenererPatron(Devis $devis)
    {
        $categorieId = $devis->categorie_id;

        // Charger uniquement les attributs requis

        $attributs = Attribut::with(['valeurs' => function ($query) use ($categorieId) {
            $query->whereHas('elementsPatron', function ($q) use ($categorieId) {
                $q->where('categorie_id', $categorieId);
            });
        }])
            ->where('obligatoire', true)
            ->whereHas('valeurs.elementsPatron', function ($q) use ($categorieId) {
                $q->where('categorie_id', $categorieId);
            })
            ->get();

        return view('devis.generer-patron', [
            'devi' => $devis,
            'attributs' => $attributs
        ]);
    }

    public function genererPatron(Request $request, Devis $devis)
    {
        // Ici tu traiteras la génération du patron selon les données reçues
    }


    public function index(Request $request)
    {
        $filtre = $request->get('filtre');

        $query = Devis::query();

        if ($filtre === 'nouvelles') {
            $query->where('statut','en_attente');
        } elseif ($filtre === 'proposes') {
            $query->whereNotNull('tarif')->where('statut','en_attente'); // pas encore accepté/refusé
        } elseif ($filtre === 'acceptes') {
            $query->where('statut', 'aceptee');
        } elseif ($filtre === 'refuses') {
            $query->where('statut', 'refusee');
        }

        $devis = $query->latest()->paginate(10);

        return view('devis.index', compact('devis'));
    }


    public function create()
    {
        $categories = Categorie::leaf()->get();
        $attributs = Attribut::with('valeurs')->get();


        return view('devis.create', compact('categories', 'attributs'));
    }

    public function createClient()
    {
        $categories = Categorie::leaf()->get();
        $attributs = Attribut::with('valeurs')->get();

        $categorieId = session('devis.categorie_id');
        $selectedValeurs = session('devis.attributs', []);
        return view('devis.request', compact('categories', 'attributs','categorieId', 'selectedValeurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'categorie_id' => 'required|exists:categories,id',
            'attribut_valeurs' => 'nullable|array',
            'attribut_valeurs.*' => 'exists:attribut_valeurs,id',
        ]);

        $valeursSelectionnees = collect($request->input('attribut_valeurs', []));

        $attributsObligatoires = Attribut::with('valeurs')->where('obligatoire', true)->get();

        $erreurs = [];
        foreach ($attributsObligatoires as $attribut) {
            $idsValeurs = $attribut->valeurs->pluck('id');
            if ($valeursSelectionnees->intersect($idsValeurs)->isEmpty()) {
                $erreurs[] = "Vous devez sélectionner au moins une valeur pour l’attribut obligatoire « {$attribut->nom} ».";
            }
        }
        if (!empty($erreurs)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $erreurs
                ], 422);
            }

            return back()
                ->withInput()
                ->withErrors(['attribut_valeurs' => implode(' ', $erreurs)]);
        }


        $data = $request->only(['description', 'categorie_id']);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('devis_images', 'public');
        }

        $devis = \App\Models\Devis::create($data);
        $devis->attributValeurs()->sync($valeursSelectionnees);
        session()->forget('devis');


        return redirect()->back()->with('message', 'Devis créé avec succès');
    }


    public function show(Devis $devi)
    {
        $devi->load('categorie', 'attributValeurs.attribut');
        return view('devis.show', compact('devi'));
    }

    public function showClient(Devis $devi)
    {
        // Vérifie que le client est bien l'utilisateur connecté
        if ($devi->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }

        // Charger les relations utiles
        $devi->load('categorie', 'attributValeurs.attribut');

        return view('devis.show-client', compact('devi'));
    }


    public function edit(Devis $devi)
    {
        $categories = Categorie::leaf()->get();
        $attributs = Attribut::with('valeurs')->get();
        $selectedValeurs = $devi->attributValeurs()->pluck('attribut_valeur_id')->toArray();

        return view('devis.edit', compact('devi', 'categories', 'attributs', 'selectedValeurs'));
    }

    public function update(Request $request, Devis $devi)
    {
        $data = $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'attribut_valeurs' => 'nullable|array',
            'attribut_valeurs.*' => 'exists:attribut_valeurs,id',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('devis', 'public');
        }

        $devi->update($data);

        // Récupère les IDs sélectionnés via les radios
        $selectedValeurs = array_values($data['attribut_valeurs'] ?? []);
        $devi->attributValeurs()->sync($selectedValeurs);

        return redirect()->route('devis.show', $devi)->with('success', 'Devis mis à jour.');
    }


    public function destroy(Devis $devi)
    {
        if ($devi->image) {
            Storage::disk('public')->delete($devi->image);
        }

        $devi->delete();
        return redirect()->route('devis.index')->with('message', 'Devis supprimé avec succès');
    }
}
