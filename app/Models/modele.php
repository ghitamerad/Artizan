<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modele extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'prix', 'categorie_id', 'patron', 'xml', 'stock', 'sur_commande'];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function detailsCommandes()
{
    return $this->hasMany(DetailCommande::class);
}

/**
 * Get all of the modele for the modele
 *
 * @return \Illuminate\Database\Eloquent\Relations\HasMany
 */
public function mesures(): HasMany
{
    return $this->hasMany(mesure::class);
}

}
