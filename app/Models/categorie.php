<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    /** @use HasFactory<\Database\Factories\CategorieFactory> */
    use HasFactory;

    protected $fillable = ['nom', 'image', 'fichier_mesure', 'categorie_id'];

        /**
     * Modèles associés à la catégorie
     */
    public function modeles()
    {
        return $this->hasMany(Modele::class);
    }

    public function parent()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function enfants()
    {
        return $this->hasMany(Categorie::class, 'categorie_id');
    }

    public function elementsPatron()
{
    return $this->hasMany(ElementPatron::class);
}

public function devis()
{
    return $this->hasMany(Devis::class);
}

public function scopeLeaf($query)
{
    return $query->doesntHave('enfants');
}

}
