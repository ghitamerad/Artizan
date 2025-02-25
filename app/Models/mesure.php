<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class mesure extends Model
{
    /** @use HasFactory<\Database\Factories\MesureFactory> */
    use HasFactory;

    protected $fillable = ['modele_id',	'label', 'valeur_par_defaut', 'variable_xml'];


    /**
     * Get the modele that owns the mesure
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modele(): BelongsTo
    {
        return $this->belongsTo(modele::class, 'modele_id');
    }
}
