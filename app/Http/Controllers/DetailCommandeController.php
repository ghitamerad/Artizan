<?php

namespace App\Http\Controllers;

use App\Models\DetailCommande;
use App\Http\Requests\StoreDetailCommandeRequest;
use App\Http\Requests\UpdateDetailCommandeRequest;
use Illuminate\Http\Request;
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
        return view('commandes.detail_commande', compact('detail_commande', 'couturieres'));
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DetailCommande $DetailCommande)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDetailCommandeRequest $request, DetailCommande $DetailCommande)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetailCommande $DetailCommande)
    {
        //
    }
}
