<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\DetailCommande;
use App\Models\modele;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        $modeles = modele::all();
        return view('commandes.create', compact('modeles'));
    }
    /**
     * Afficher une commande spécifique.
     */
    public function show(Commande $commande)
    {
        $this->authorize('view', $commande);
        $couturieres = User::where('role', 'couturiere')->get(); // Récupérer les utilisateurs avec le rôle couturière
        $commande->load('details.modele'); // Charge les détails et modèles associés
        return view('commandes.show', compact('commande', 'couturieres'));
    }



       public function store(Request $request)
{
    DB::beginTransaction();

    try {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return response()->json(['error' => 'Utilisateur non authentifié.'], 401);
        }

        $userId = Auth::id();

        // Récupérer le panier depuis le cache
        $panier = Cache::get("panier_{$userId}", []);

        if (empty($panier)) {
            return response()->json(['error' => 'Votre panier est vide.'], 400);
        }

        // Calcul du montant total
        $montant_total = array_reduce($panier, function ($total, $item) {
            return $total + ($item['prix'] * $item['quantite']);
        }, 0);

        // Création de la commande
        $commande = Commande::create([
            'user_id' => $userId,
            'montant_total' => $montant_total,
            'statut' => 'en_attente',
        ]);

        // Ajouter les modèles du panier à la commande
        foreach ($panier as $item) {
            DetailCommande::create([
                'commande_id' => $commande->id,
                'modele_id' => $item['id'],
                'quantite' => $item['quantite'],
                'prix_unitaire' => $item['prix'],
            ]);
        }

        // Vider le panier après validation de la commande
        Cache::forget("panier_{$userId}");

        DB::commit();

        return redirect()->route('commandes.index')->with('success', 'Commande enregistrée avec succès.');
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Une erreur est survenue', 'message' => $e->getMessage()], 500);
    }
}



    public function edit(Commande $commande)
    {
        $this->authorize('update', $commande); // Vérifie les permissions

        $users = User::all(); // Récupère tous les utilisateurs
        $modeles = modele::all();

        return view('commandes.edit', compact('commande', 'users','modeles'));
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

        $commande->update(['statut' => 'annulee']);

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
            'statut' => 'sometimes|string|in:en_attente,validee,anulee',
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
