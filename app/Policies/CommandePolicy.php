<?php

namespace App\Policies;

use App\Models\Commande;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommandePolicy
{
   /**
     * Déterminer si un utilisateur peut voir la liste des commandes.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'gerante']);
    }

    /**
     * Déterminer si un utilisateur peut voir une commande spécifique.
     */
    public function view(User $user, Commande $commande): bool
    {
        return in_array($user->role, ['admin', 'gerante']) || $user->id === $commande->user_id;
    }

    /**
     * Déterminer si un utilisateur peut créer une commande.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Déterminer si un utilisateur peut mettre à jour une commande.
     */
    public function update(User $user, Commande $commande): bool
    {
        return in_array($user->role, ['admin', 'gerante']);
    }

    /**
     * Déterminer si un utilisateur peut supprimer une commande.
     */
    public function delete(User $user, Commande $commande): bool
    {
        return in_array($user->role, ['admin', 'gerante']);
    }

    /**
     * Déterminer si un utilisateur peut valider ou invalider une commande.
     */
    public function validateCommande(User $user, Commande $commande): bool
    {
        return in_array($user->role, ['admin', 'gerante']);
    }

}

