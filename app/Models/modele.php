<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class modele extends Model
{
    /** @use HasFactory<\Database\Factories\ModeleFactory> */
    use HasFactory;

    protected $fillable = ['nom', 'description', 'categorie_id', 'prix', 'patron', 'xml', 'en_stock'];

    public function categorie()
    {
        return $this->belongsTo(categorie::class, 'categorie_id');
    }

    public function detailsCommandes()
{
    return $this->hasMany(DetailCommande::class);
}

}
