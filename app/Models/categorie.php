<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categorie extends Model
{
    /** @use HasFactory<\Database\Factories\CategorieFactory> */
    use HasFactory;

    protected $fillable = ['nom', 'image', 'fichier_mesure', 'categorie_id'];

    public function parent()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function enfants()
    {
        return $this->hasMany(Categorie::class, 'categorie_id');
    }
}
