<?php

namespace App\Policies;

use App\Models\User;
use App\Models\modele;

class ModelePolicy
{
    /**
     * Déterminer si un utilisateur peut voir tous les modèles.
     */
    public function viewAny(User $user): bool
    {
        return true; // Tout utilisateur peut voir la liste des modèles
    }

    /**
     * Déterminer si un utilisateur peut voir un modèle spécifique.
     */
    public function view(User $user, modele $modele): bool
    {
        return $user->role === 'gerante';
    }

    /**
     * Déterminer si un utilisateur peut créer un modèle.
     */
    public function create(User $user): bool
    {

        return $user->role === 'gerante';
    }

    /**
     * Déterminer si un utilisateur peut modifier un modèle.
     */
    public function update(User $user, modele $modele): bool
    {

        return $user->role === 'gerante';
    }

    /**
     * Déterminer si un utilisateur peut supprimer un modèle.
     */
    public function delete(User $user, modele $modele): bool
    {
        return $user->role === 'gerante';
    }
}
