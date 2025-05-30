<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telephone', // ✅ Ajout du téléphone
    ];

    /**
     * Relation avec les commandes passées par le client ou créées par la gérante.
     */
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasRole($roles)
{
    return in_array($this->role, (array) $roles);
}

/**
 * Get all of the comments for the User
 *
 * @return \Illuminate\Database\Eloquent\Relations\HasMany
 */
public function details(): HasMany
{
    return $this->hasMany(DetailCommande::class);
}
}
