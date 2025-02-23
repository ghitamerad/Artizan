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
        return view('commandes.index', compact('commandes'));
    }
    public function create()
    {
        $this->authorize('create', Commande::class); // Vérifie si l'utilisateur peut créer une commande

        return view('commandes.create');
    }
    /**
     * Afficher une commande spécifique.
     */
    public function show(Commande $commande)
    {
        $this->authorize('view', $commande);
        $commande->load('details.modele'); // Charge les détails et modèles associés
        return view('commandes.show', compact('commande'));
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

        public function edit(Commande $commande)
{
    $this->authorize('update', $commande); // Vérifie les permissions

    return view('commandes.edit', compact('commande'));
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


    public function update(Request $request, Commande $commande)
{
    $this->authorize('update', $commande);

    // Validation des données
    $request->validate([
        'modeles' => 'sometimes|array', // Permet une mise à jour optionnelle des modèles
        'modeles.*.id' => 'required_with:modeles|exists:modeles,id',
        'modeles.*.quantite' => 'required_with:modeles|integer|min:1',
        'modeles.*.prix_unitaire' => 'required_with:modeles|numeric|min:0',
        'montant_total' => 'sometimes|numeric|min:0',
        'statut' => 'sometimes|string|in:en_attente,validee,refusee',
    ]);

    DB::beginTransaction();

    try {
        // Mise à jour des champs de la commande si fournis
        $commande->update($request->only(['montant_total', 'statut']));

        // Vérifier si des modèles sont fournis pour mise à jour
        if ($request->has('modeles')) {
            // Supprimer les anciens détails et insérer les nouveaux
            $commande->details()->delete(); // Supprime les anciens détails
            foreach ($request->modeles as $modele) {
                DetailCommande::create([
                    'commande_id' => $commande->id,
                    'modele_id' => $modele['id'],
                    'quantite' => $modele['quantite'],
                    'prix_unitaire' => $modele['prix_unitaire'],
                    'fichier_patron' => $modele['fichier_patron'] ?? null,
                ]);
            }
        }

        DB::commit();

        return response()->json(['message' => 'Commande mise à jour avec succès'], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Une erreur est survenue', 'message' => $e->getMessage()], 500);
    }
}



}
