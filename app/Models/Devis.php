<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    /** @use HasFactory<\Database\Factories\DevisFactory> */
    use HasFactory;

    protected $fillable = ['description', 'image', 'categorie_id', 'user_id','statut', 'tarif', 'chemin_patron'];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attributValeurs()
    {
        return $this->belongsToMany(AttributValeur::class, 'attribut_valeur_devis','devis_id','attribut_valeur_id');
    }

}
