<?php

namespace App\Policies;

use App\Models\Commande;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommandePolicy
{
    /**
     * Déterminer si un utilisateur peut créer une commande.
     */
    public function create(User $user): bool
    {
        return $user->role === 'client';
    }

    /**
     * Déterminer si un utilisateur peut valider/invalider une commande.
     */
    public function validate(User $user, Commande $commande): bool
    {
        return $user->role === 'gerante';
    }

    /**
     * Déterminer si un utilisateur peut assigner une commande à une couturière.
     */
    public function assign(User $user, Commande $commande): bool
    {
        return $user->role === 'gerante';
    }

    /**
     * Déterminer si un utilisateur peut confirmer une commande.
     */
    public function confirm(User $user, Commande $commande): bool
    {
        return $user->role === 'couturiere';
    }
}

