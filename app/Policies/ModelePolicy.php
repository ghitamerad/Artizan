<?php

namespace App\Policies;

use App\Models\User;
use App\Models\modele;
use Illuminate\Auth\Access\Response;

class ModelePolicy
{
    public function create(User $user)
    {
        return $user->role === 'gerante'; // Assurez-vous que le rôle de la gérante est bien défini
    }
}
