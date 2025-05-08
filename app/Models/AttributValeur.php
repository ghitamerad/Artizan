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
        return $this->belongsToMany(Devis::class)
                    ->wherePivot('approved', 1);
    }

    public function modeles()
    {
        return $this->belongsToMany(Modele::class)
                    ->wherePivot('approved', 1);
    }
}
