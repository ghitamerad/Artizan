<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\DetailCommande;
use App\Models\Modele;
use App\Models\Devis;
use App\Models\Mesure;
use App\Models\User;
use App\Notifications\CommandeTerminee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Notifications\NouvelleCommandeNotification;
use App\Notifications\CommandeCreee;
use Illuminate\Support\Facades\Notification;


class CommandeController extends Controller
{

    /**
     * Afficher la liste des commandes (réservé aux admins et gérantes).
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Commande::class);

        $filtre = $request->get('filtre');

        $commandes = Commande::query();

        if ($filtre === 'nouvellesCommandes') {
            $commandes->where('statut', 'en_attente');
        } elseif ($filtre === 'encours') {
            $commandes->where('statut', 'validee')
                ->orWhere('statut', 'assigner');
        } elseif ($filtre === 'terminees') {
            $commandes->where('statut', 'expediee');
        } elseif ($filtre === 'refusees') {
            $commandes->where('statut', 'annulee');
        } elseif ($filtre === 'expedier') {
            $commandes->where('statut', 'expediee');
        }

        $commandes = $commandes->latest()->paginate(10);

        return view('commandes.index', compact('commandes', 'filtre'));
    }
    public function create()
    {
        $this->authorize('create', Commande::class); // Vérifie si l'utilisateur peut créer une commande
        $modeles = Modele::latest()->get();
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

    public function expedier(Commande $commande)
    {
        $this->authorize('validateCommande', $commande);

        if (!in_array($commande->statut, ['validee', 'assignee'])) {
            return redirect()->route('commandes.index')
                ->with('error', 'Seules les commandes validées ou assignées peuvent être expédiées.');
        }

        $commande->statut = 'expediee';
        $commande->save();

        return redirect()->back()
            ->with('success', 'La commande a été marquée comme expédiée.');
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
            foreach ($panier as $modeleId => $item) {
                $quantite = $item['quantite'];
                $prixUnitaire = $item['prix'];

                // Récupérer les mesures spécifiques du cache pour ce modèle et cet utilisateur
                $mesuresClient = Cache::get("mesures_user_{$userId}_modele_{$modeleId}", []);

                // Déterminer si la commande est sur mesure (custom)
                $custom = !empty($mesuresClient);

                // Création du détail de commande
                $detailCommande = DetailCommande::create([
                    'commande_id' => $commande->id,
                    'modele_id' => $modeleId,
                    'quantite' => $quantite,
                    'prix_unitaire' => $prixUnitaire,
                    'custom' => $custom, // ✅ Initialisation correcte
                ]);

                if ($custom) {
                    // Récupérer les mesures du modèle en base
                    $mesuresModel = \App\Models\Mesure::where('modele_id', $modeleId)->get();

                    if ($mesuresModel->isEmpty()) {
                        throw new \Exception("Aucune mesure trouvée pour le modèle ID : $modeleId");
                    }

                    foreach ($mesuresModel as $mesure) {
                        // Récupérer la valeur fournie par le client ou la valeur par défaut
                        $key = $mesure->label ?? $mesure->nom;
                        $valeurMesure = $mesuresClient[$key] ?? $mesure->valeur_par_defaut;

                        Log::info("Traitement mesure pour le modèle {$modeleId}", [
                            'mesure_id' => $mesure->id,
                            'nom' => $key,
                            'valeur_donnée_client' => $mesuresClient[$key] ?? null,
                            'valeur_utilisée' => $valeurMesure,
                            'valeur_par_defaut' => $mesure->valeur_par_defaut,
                            'variable_xml' => $mesure->variable_xml,
                        ]);

                        \App\Models\MesureDetailCommande::create([
                            'mesure_id' => $mesure->id,
                            'details_commande_id' => $detailCommande->id,
                            'valeur_mesure' => $valeurMesure,
                            'valeur_par_defauts' => $mesure->valeur_par_defaut,
                            'variable_xml' => $mesure->variable_xml,
                        ]);
                    }
                }
            }

            // Récupérer les gérantes
            $gerantes = User::where('role', 'gerante')->get();

            // Notifier les gérantes
            foreach ($gerantes as $gerante) {
                $gerante->notify(new NouvelleCommandeNotification($commande));
            }
            // Vider le panier après validation de la commande
            Cache::forget("panier_{$userId}");

            DB::commit();

            return redirect()->route('detail-commandes.index')->with('success', 'Commande enregistrée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Une erreur est survenue', 'message' => $e->getMessage()], 500);
        }
    }


    public function createFromDevis(Devis $devis)
    {
        $this->authorize('create', Commande::class);

        $client = $devis->utilisateur;
        $modele = Modele::findOrFail($devis->modele_id);
        $mesures = $modele->mesures;

        return view('commandes.create_from_devis', compact('devis', 'client', 'modele', 'mesures'));
    }

    public function storeFromDevis(Request $request, Devis $devis)
    {
        Log::info('📩 Formulaire reçu', $request->all());

        DB::beginTransaction();

        try {
            Log::info('🔍 Début création commande depuis devis', [
                'devis_id' => $devis->id,
                'user_id' => $devis->user_id,
                'modele_id' => $devis->modele_id
            ]);

            $request->validate([
                'quantite' => 'required|integer|min:1',
                'custom' => 'nullable|boolean',
                'mesures' => 'nullable|array',
                'mesures.*' => 'nullable|numeric|min:0',
            ]);

            $userId = $devis->user_id;
            $modele = $devis->modele;
            $quantite = $request->quantite;
            $prixUnitaire = $modele->prix;

            // Création de la commande
            $commande = Commande::create([
                'user_id' => $userId,
                'statut' => 'validee',
                'montant_total' => $prixUnitaire * $quantite, // ✅ plus fiable que $devis->tarif
            ]);

            Log::info('✅ Commande créée', ['commande_id' => $commande->id]);

            // Création du détail de commande
            $detail = DetailCommande::create([
                'commande_id' => $commande->id,
                'modele_id' => $devis->modele_id,
                'quantite' => $request->quantite,
                'prix_unitaire' =>  $prixUnitaire,
                'custom' => $request->boolean('custom'),
            ]);

            Log::info('✅ Détail commande créé', ['detail_id' => $detail->id, 'custom' => $detail->custom]);

            if ($request->boolean('custom')) {
                Log::info('🧵 Traitement mesures personnalisées activé');

                // Récupérer les mesures du modèle
                $mesuresModel = \App\Models\Modele::findOrFail($devis->modele_id)->mesures;

                foreach ($mesuresModel as $mesure) {
                    $valeur = $request->mesures[$mesure->id] ?? $mesure->valeur_par_defaut;

                    Log::info("📐 Mesure traitée", [
                        'mesure_id' => $mesure->id,
                        'label' => $mesure->label,
                        'valeur_saisie' => $request->mesures[$mesure->id] ?? null,
                        'valeur_enregistrée' => $valeur
                    ]);

                    \App\Models\MesureDetailCommande::create([
                        'mesure_id' => $mesure->id,
                        'details_commande_id' => $detail->id,
                        'valeur_mesure' => $valeur,
                        'valeur_par_defauts' => $mesure->valeur_par_defaut,
                        'variable_xml' => $mesure->variable_xml,
                    ]);
                }
            }

            // Notifier le client
            $client = $devis->utilisateur;
            Notification::send($client, new \App\Notifications\CommandeCreee($commande, $detail));

            Log::info("📬 Notification envoyée au client", ['client_id' => $client->id]);

            DB::commit();

            return redirect()->route('commandes.show', $commande)->with('success', 'Commande créée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Erreur lors de la création de commande à partir du devis', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function edit(Commande $commande)
    {
        $this->authorize('update', $commande); // Vérifie les permissions

        $users = User::all(); // Récupère tous les utilisateurs
        $modeles = Modele::all();

        return view('commandes.edit', compact('commande', 'users', 'modeles'));
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

        $client = $commande->user;

        $commande->update(['statut' => 'validee']);
        $client->notify(new CommandeTerminee($commande));


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
