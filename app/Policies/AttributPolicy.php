<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Attribut;

class AttributPolicy
{
    /**
     * Déterminer si l'utilisateur peut voir la liste des attributs.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'gerante']);
    }

    /**
     * Déterminer si l'utilisateur peut voir un attribut spécifique.
     */
    public function view(User $user, Attribut $attribut): bool
    {
        return in_array($user->role, ['admin', 'gerante']);
    }

    /**
     * Déterminer si l'utilisateur peut créer un attribut.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'gerante']);
    }

    /**
     * Déterminer si l'utilisateur peut modifier un attribut.
     */
    public function update(User $user, Attribut $attribut): bool
    {
        return in_array($user->role, ['admin', 'gerante']);
    }

    /**
     * Déterminer si l'utilisateur peut supprimer un attribut.
     */
    public function delete(User $user, Attribut $attribut): bool
    {
        return in_array($user->role, ['admin', 'gerante']);
    }

    /**
     * (optionnel) Restaurer un attribut supprimé
     */
    public function restore(User $user, Attribut $attribut): bool
    {
        return false;
    }

    /**
     * (optionnel) Supprimer définitivement un attribut
     */
    public function forceDelete(User $user, Attribut $attribut): bool
    {
        return false;
    }
}
