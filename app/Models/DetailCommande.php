<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailCommande extends Model
{
    /** @use HasFactory<\Database\Factories\DetailCommandeFactory> */
    use HasFactory;


    protected $fillable = [
        'commande_id',
        'modele_id',
        'quantite',
        'prix_unitaire',
    ];

    /**
     * Relation avec la commande (1 commande peut avoir plusieurs détails).
     */
    public function commande()
    {
        return $this->belongsTo(commande::class, 'commande_id');
    }

    /**
     * Relation avec le modèle (1 modèle est lié à plusieurs détails de commande).
     */
    public function modele()
    {
        return $this->belongsTo(modele::class, 'modele_id');
    }
}
