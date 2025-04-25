<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribut extends Model
{
    /** @use HasFactory<\Database\Factories\AttributFactory> */
    use HasFactory;

    protected $fillable = ['nom'];

    public function modeles()
    {
        return $this->belongsToMany(Modele::class, 'attribut_modeles', 'attribut_id', 'modele_id');
    }

}
