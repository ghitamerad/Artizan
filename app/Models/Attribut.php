<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Attribut extends Model
{
    /** @use HasFactory<\Database\Factories\AttributFactory> */
    use HasFactory;

    protected $fillable = ['nom', 'obligatoire'];


public function valeurs(): HasMany
{
    return $this->hasMany(AttributValeur::class);
}
}
