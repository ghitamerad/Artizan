<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\DetailCommande;
use App\Models\modele;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommandeController extends Controller
{

        /**
     * Afficher la liste des commandes (réservé aux admins et gérantes).
     */
    public function index()
    {
        $this->authorize('viewAny', Commande::class);

        $commandes = Commande::all();
        return view('admin.commandes.index', compact('commandes'));
    }

    /**
     * Afficher une commande spécifique.
     */
    public function show(Commande $commande)
    {
        $this->authorize('view', $commande);

        return view('admin.commandes.show', compact('commande'));
    }

        public function store(Request $request)
        {
            // Validation des données
            $request->validate([
                'modeles' => 'required|array',
                'modeles.*.id' => 'required|exists:modeles,id',
                'modeles.*.quantite' => 'required|integer|min:1',
                'modeles.*.prix_unitaire' => 'required|numeric|min:0',
                'montant_total' => 'required|numeric|min:0',
            ]);

            DB::beginTransaction(); // Démarrer une transaction pour éviter les erreurs

            try {
                // 1️⃣ Création de la commande
                $commande = Commande::create([
                    'user_id' => Auth::id(),
                    'montant_total' => $request->montant_total,
                    'statut' => 'en_attente',
                ]);

                // 2️⃣ Ajout des détails de la commande
                foreach ($request->modeles as $modele) {
                    DetailCommande::create([
                        'commande_id' => $commande->id, // ID de la commande créée
                        'modele_id' => $modele['id'],
                        'quantite' => $modele['quantite'],
                        'prix_unitaire' => $modele['prix_unitaire'],
                        'fichier_patron' => $modele['fichier_patron'] ?? null, // Optionnel
                    ]);
                }

                DB::commit(); // Validation de la transaction

                return response()->json(['message' => 'Commande enregistrée avec succès', 'commande_id' => $commande->id], 201);
            } catch (\Exception $e) {
                DB::rollBack(); // Annuler toute l'opération en cas d'erreur
                return response()->json(['error' => 'Une erreur est survenue', 'message' => $e->getMessage()], 500);
            }
        }




    /**
     * Supprimer une commande.
     */
    public function destroy(Commande $commande)
    {
        $this->authorize('delete', $commande);

        $commande->delete();

        return redirect()->route('commandes.index')->with('success', 'Commande supprimée avec succès.');
    }

    /**
     * Valider une commande (réservé aux admins et gérantes).
     */
    public function validateCommande(Commande $commande)
    {
        $this->authorize('validateCommande', $commande);

        $commande->update(['statut' => 'validee']);

        return redirect()->back()->with('success', 'Commande validée.');
    }

    /**
     * Invalider une commande (réservé aux admins et gérantes).
     */
    public function unvalidateCommande(Commande $commande)
    {
        $this->authorize('validateCommande', $commande);

        $commande->update(['statut' => 'refusee']);

        return redirect()->back()->with('success', 'Commande invalidée.');
    }


}
