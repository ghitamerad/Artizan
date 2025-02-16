<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;
use App\Models\modele;
use App\Models\commande;
use App\Policies\UserPolicy;
use App\Policies\ModelePolicy;
use App\Policies\CommandePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        modele::class => ModelePolicy::class,
        commande::class => CommandePolicy::class,


    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
