<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class modele extends Model
{
    /** @use HasFactory<\Database\Factories\ModeleFactory> */
    use HasFactory;

    protected $fillable = ['nom', 'description', 'categorie_id', 'prix','patron','xml'];

    public function categorie()
    {
        return $this->belongsTo(categorie::class, 'categorie_id');
    }
}
