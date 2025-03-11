<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailCommande extends Model
{
    /** @use HasFactory<\Database\Factories\DetailCommandeFactory> */
    use HasFactory;


    protected $fillable = [
        'user_id',
        'commande_id',
        'modele_id',
        'statut',
        'quantite',
        'prix_unitaire',
        'cutom',
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
/**
 * Get the user that owns the DetailCommande
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
 */
public function couturiere(): BelongsTo
{
    return $this->belongsTo(User::class, 'user_id');
}
}
