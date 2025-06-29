<?php

namespace App\Http\Controllers;

use App\Models\DetailCommande;
use App\Models\Commande;
use App\Models\MesureDetailCommande;
use App\Http\Requests\StoreDetailCommandeRequest;
use App\Http\Requests\UpdateDetailCommandeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\CommandeTerminee;
use App\Notifications\DetailCommandeTermine;
use Illuminate\Support\Facades\Auth;



class DetailCommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $filtre = request('filtre', 'toutes');

        // 1. Récupérer les détails de commandes filtrés
        $query = DetailCommande::whereHas('commande', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });

        switch ($filtre) {
            case 'en_cours':
                $query->where('statut', 'en_attente');
                break;
            case 'terminees':
                $query->where('statut', 'validee');
                break;
            case 'toutes':
            default:
                // Aucun filtre supplémentaire
                break;
        }

        $detailCommandes = $query->with('commande')->latest()->get();

        // 2. Récupérer les commandes en cours (statut 'en_attente' ou 'validee')
        $commandesEnCours = Commande::where('user_id', $userId)
            ->whereIn('statut', ['en_attente', 'validee'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Récupérer les commandes précédentes (statut 'expediee')
        $commandesPrecedentes = Commande::where('user_id', $userId)
            ->where('statut', 'expediee')
            ->orderBy('created_at', 'desc')
            ->get();

        $slot = '';

        return view('detail_commande.index', compact(
            'detailCommandes',
            'filtre',
            'commandesEnCours',
            'commandesPrecedentes',
            'slot'
        ));
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
    public function store(StoreDetailCommandeRequest $request)
    {
        //
    }

    public function showClient($id)
    {
        $commande = Commande::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $detailsCommande = DetailCommande::where('commande_id', $commande->id)->get();

        return view('detail_commande.showClient', compact('commande', 'detailsCommande'));
    }


    /**
     * Display the specified resource.
     */

    public function show(DetailCommande $detail_commande)
    {
        $couturieres = User::where('role', 'couturiere')->get(); // Récupérer les utilisateurs avec le rôle couturière
        return view('detail_commande.show', compact('detail_commande', 'couturieres'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $detail_commande = DetailCommande::findOrFail($id);
        $couturieres = User::where('role', 'couturiere')->get();

        return view('detail_commande.edit', compact('detail_commande', 'couturieres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $detailCommande = DetailCommande::findOrFail($id);

        // Validation de base
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'statut' => 'required|in:Null,validee,refuser,fini',
            'quantite' => 'required|integer|min:1',
            'prix_unitaire' => 'required|numeric|min:0',
            'custom' => 'nullable|boolean',
            'mesures' => 'nullable|array',
            'mesures.*' => 'nullable|numeric|min:0',
        ]);

        // 💡 Validation dynamique des mesures avec min / max
        if ($request->has('mesures')) {
            foreach ($request->mesures as $mesureId => $valeur) {
                $mesureDetail = $detailCommande->mesuresDetail()->where('id', $mesureId)->first();

                if ($mesureDetail && $mesureDetail->mesure) {
                    $min = $mesureDetail->mesure->min;
                    $max = $mesureDetail->mesure->max;

                    if ($valeur < $min || $valeur > $max) {
                        return redirect()->back()->withInput()->withErrors([
                            "mesures.$mesureId" => "La valeur de la mesure '{$mesureDetail->mesure->label}' doit être entre $min et $max.",
                        ]);
                    }
                }
            }
        }

        // Mise à jour des champs principaux
        $detailCommande->update([
            'user_id' => $request->user_id,
            'statut' => $request->statut,
            'quantite' => $request->quantite,
            'prix_unitaire' => $request->prix_unitaire,
            'custom' => $request->custom,
        ]);

        // Mise à jour des mesures si tout est OK
        if ($request->has('mesures')) {
            foreach ($request->mesures as $mesureId => $valeur) {
                $mesure = $detailCommande->mesuresDetail()->where('id', $mesureId)->first();
                if ($mesure) {
                    $mesure->update(['valeur_mesure' => $valeur]);
                }
            }
        }

        return redirect()->route('commandes.detail_commande', $detailCommande->id)
            ->with('success', 'Détails de la commande mis à jour avec succès');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetailCommande $DetailCommande)
    {
        //
    }

    public function updateMesures(Request $request, $id)
    {
        $detail_commande = DetailCommande::findOrFail($id);

        // Validation des mesures envoyées
        $request->validate([
            'mesures' => 'required|array',
            'mesures.*.id' => 'required|exists:mesure_detail_commandes,id',
            'mesures.*.valeur_mesure' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->mesures as $mesureData) {
                $mesureDetail = MesureDetailCommande::findOrFail($mesureData['id']);
                $mesureModele = $mesureDetail->mesure; // relation vers le modèle Mesure

                $valeur = $mesureData['valeur_mesure'];

                if ($valeur < $mesureModele->min || $valeur > $mesureModele->max) {
                    return redirect()->back()->with('error', "La valeur de la mesure '{$mesureModele->label}' doit être entre {$mesureModele->min} et {$mesureModele->max}.");
                }

                $mesureDetail->update(['valeur_mesure' => $valeur]);
            }


            DB::commit();
            return redirect()->back()->with('success', 'Mesures mises à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Assigner une commande à une couturière.
     */
    public function assignerCouturiere(Request $request, $detailId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $detail = DetailCommande::findOrFail($detailId);
        $detail->user_id = $request->user_id;
        $detail->save();

        $commande = $detail->commande;


        $couturiere = \App\Models\User::find($request->user_id);
        $couturiere->notify(new \App\Notifications\CouturiereAssignee($commande, $detail));

        // Récupérer la commande associée
        $commande = $detail->commande;

        // Vérifier si tous les détails de la commande où `custom = true` ont un `user_id` non null
        $tousAssignes = $commande->details()
            ->where('custom', true)
            ->whereNull('user_id') // Vérifier si des détails n'ont pas encore de couturière assignée
            ->doesntExist();

        // Si tous les détails `custom = true` ont un `user_id`, on met à jour la commande en "assignée"
        if ($tousAssignes) {
            $commande->update(['statut' => 'assigner']);
            $client = $commande->user;
            $client->notify(new CommandeTerminee($commande));

        }

        return redirect()->route('commandes.show', $commande->id)
            ->with('success', 'Couturière assignée avec succès.');
    }



    public function commandesCouturiere()
    {
        $commandesEnCours = DetailCommande::where('user_id', Auth::id())
            ->where('statut', 'validee')
            ->with(['modele', 'commande.user'])
            ->latest()
            ->get();

        $commandesTerminees = DetailCommande::where('user_id', Auth::id())
            ->where('statut', 'fini')
            ->with(['modele', 'commande.user'])
            ->latest()
            ->get();

        return view('couturiere.commandes', compact('commandesEnCours', 'commandesTerminees'));
    }

public function terminerCommande($id)
{
    $commandeDetail = DetailCommande::findOrFail($id);

    if ($commandeDetail->user_id == Auth::id()) {
        $commandeDetail->update(['statut' => 'fini']);

        $commande = $commandeDetail->commande;
        $client = $commande->user;

        // 🔔 Notifier le client que ce détail est terminé
        $client->notify(new \App\Notifications\DetailCommandeTermine($commandeDetail));

        // ✅ Vérifier si tous les détails personnalisés sont finis
        $tousFinis = $commande->details()
            ->where('custom', true)
            ->where('statut', '!=', 'fini')
            ->doesntExist();

        if ($tousFinis) {
            $commande->update(['statut' => 'validee']);


            // 🔔 Notifier la responsable
            $responsables = \App\Models\User::where('role', 'gerante')->get();

            foreach ($responsables as $responsable) {
                $responsable->notify(new \App\Notifications\CommandeTermineeParCouturiere($commandeDetail));
            }
        }

        return redirect()->route('couturiere.commandes')->with('success', 'Détail de la commande terminé.');
    }

    return redirect()->route('couturiere.commandes')->with('error', 'Action non autorisée.');
}

}
