<?php

namespace App\Http\Controllers;

use App\Models\DetailCommande;
use App\Models\MesureDetailCommande;
use App\Http\Requests\StoreDetailCommandeRequest;
use App\Http\Requests\UpdateDetailCommandeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;



class DetailCommandeController extends Controller
{
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
    public function store(StoreDetailCommandeRequest $request)
    {
        //
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

    // Validation des données entrantes
    $request->validate([
        'user_id' => 'nullable|exists:users,id',
        'statut' => 'required|in:Null,validee,refuser,fini',
        'quantite' => 'required|integer|min:1',
        'prix_unitaire' => 'required|numeric|min:0',
        'custom' => 'nullable|boolean',
        'mesures' => 'nullable|array',  // Assure que c'est un tableau
        'mesures.*' => 'nullable|numeric|min:0', // Chaque mesure doit être un nombre positif
    ]);

    // Mise à jour des champs principaux
    $detailCommande->update([
        'user_id' => $request->user_id,
        'statut' => $request->statut,
        'quantite' => $request->quantite,
        'prix_unitaire' => $request->prix_unitaire,
        'custom' => $request->custom,
    ]);

    // Mise à jour des mesures
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
                $mesureDetail->update([
                    'valeur_mesure' => $mesureData['valeur_mesure'],
                ]);
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

    return redirect()->route('commandes.show', $detail->commande->id)
        ->with('success', 'Couturière assignée avec succès.');
}


public function commandesCouturiere()
{
    $commandes = DetailCommande::where('user_id', Auth::id())
                    ->where('statut', 'validee')
                    ->get();

    return view('couturiere.commandes', compact('commandes'));
}

public function terminerCommande($id)
{
    $commande = DetailCommande::findOrFail($id);

    if ($commande->user_id == Auth::id()) {
        $commande->update(['statut' => 'fini']);
        return redirect()->route('couturiere.commandes')->with('success', 'Commande marquée comme terminée.');
    }

    return redirect()->route('couturiere.commandes')->with('error', 'Action non autorisée.');
}
}
