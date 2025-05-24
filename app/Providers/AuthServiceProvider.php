<?php

namespace App\Providers;

use App\Models\Attribut;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;
use App\Models\Modele;
use App\Models\Commande;
use App\Policies\AttributPolicy;
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
        Modele::class => ModelePolicy::class,
        Commande::class => CommandePolicy::class,
        Attribut::class => AttributPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
