<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commande extends Model
{
    /** @use HasFactory<\Database\Factories\CommandeFactory> */
    use HasFactory;


    protected $fillable = [
        'user_id',
        'montant_total',
        'statut'
    ];

    /**
     * Relation avec l'utilisateur qui a passé la commande.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
