<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Panier extends Model
{
    /** @use HasFactory<\Database\Factories\PanierFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'modele_id', 'quantite', 'prix_total'];
    protected $attributes = [
        'prix_total' => 0,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function modele(): BelongsTo
    {
        return $this->belongsTo(Modele::class);
    }
}
