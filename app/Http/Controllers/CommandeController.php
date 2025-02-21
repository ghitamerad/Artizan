<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\DetailCommande;
use App\Models\modele;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class CommandeController extends Controller
{

    use AuthorizesRequests;
    /**
     * Enregistre une nouvelle commande avec ses détails.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Commande::class); // Vérifie si l'utilisateur a le droit de créer une commande

        $user = Auth::user(); // Récupère l'utilisateur connecté

        // Création de la commande
        $commande = Commande::create([
            'user_id' => $user->id,
            'date_commande' => now(),
            'statut' => 'en_attente',
            'montant_total' => $request->montant_total,
        ]);

        // Enregistrement des détails de commande
        DetailCommande::create([
            'commande_id' => $commande->id,
            'modele_id' => $request->modele_id,
            'quantite' => 1, // Modifie selon besoin
            'prix_unitaire' => $request->montant_total,
            'fichier_patron' => null, // Gère ce champ si nécessaire
        ]);

        return redirect('/commandes')->with('success', 'Commande créée avec succès.');
    }


}
