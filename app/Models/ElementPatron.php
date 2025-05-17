<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElementPatron extends Model
{
    /** @use HasFactory<\Database\Factories\ElementPatronFactory> */
    use HasFactory;

    protected $fillable = ['fichier_patron', 'categorie_id', 'attribut_valeur_id'];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function attributValeur()
    {
        return $this->belongsTo(AttributValeur::class);
    }
}
