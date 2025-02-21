<?php

namespace App\Policies;

use App\Models\Commande;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommandePolicy
{

    public function create(User $user): bool
    {
        return true; // Tout utilisateur connecté peut commander
    }
}

