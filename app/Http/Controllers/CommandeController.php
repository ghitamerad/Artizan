<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommandeController extends Controller
{
    /**
     * Un client crée une commande.
     */
    public function store(Request $request)
    {
        $this->authorize('create', commande::class);

        $commande = commande::create([
            'user_id' => Auth::id(),
            'montant_total' => $request->montant_total,
        ]);

        return redirect()->back()->with('success', 'Commande créée avec succès !');
    }

    /**
     * Une gérante valide ou invalide une commande.
     */
    public function validateCommande($id)
    {
        $commande = Commande::findOrFail($id);
        $this->authorize('validate', $commande);

        $commande->update(['statut' => 'validee']);

        return redirect()->back()->with('success', 'Commande validée avec succès !');
    }

    public function unvalidateCommande($id)
    {
        $commande = Commande::findOrFail($id);
        $this->authorize('validate', $commande);

        $commande->update(['statut' => 'annulee']);

        return redirect()->back()->with('success', 'Commande annulée avec succès !');
    }

    /**
     * Une gérante assigne une commande à une couturière.
     */
    public function assignToCouturiere(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);
        $this->authorize('assign', $commande);

        $couturiere = User::where('role', 'couturiere')->findOrFail($request->couturiere_id);
        $commande->update(['user_id' => $couturiere->id]);

        return redirect()->back()->with('success', 'Commande assignée à la couturière avec succès !');
    }

    /**
     * Une couturière confirme une commande.
     */
    public function confirmCommande($id)
    {
        $commande = Commande::findOrFail($id);
        $this->authorize('confirm', $commande);

        $commande->update(['statut' => 'expediee']);

        return redirect()->back()->with('success', 'Commande confirmée avec succès !');
    }
}
