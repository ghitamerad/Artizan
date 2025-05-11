<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributValeur extends Model
{
    /** @use HasFactory<\Database\Factories\AttributValeurFactory> */
    use HasFactory;

    protected $fillable = ['nom', 'image', 'custom', 'attribut_id'];

    public function attribut()
    {
        return $this->belongsTo(Attribut::class);
    }

    public function devis()
    {
        return $this->belongsToMany(Devis::class,'attribut_valeur_id','devis_id');
    }

    public function modeles()
    {
        return $this->belongsToMany(Modele::class,'attribut_valeur_modele','attribut_valeur_id','modele_id');
    }
        /**
     * Les éléments de patron associés à cette valeur
     */
    public function elementsPatron()
    {
        return $this->hasMany(ElementPatron::class);
    }


}
