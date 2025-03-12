<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MesureDetailCommande extends Model
{
    /** @use HasFactory<\Database\Factories\MesureDetailCommandeFactory> */
    use HasFactory;

    protected $table = 'mesure_detail_commandes';

    protected $fillable = [
        'mesure_id',
        'details_commande_id',
        'valeur_mesure',
        'valeur_par_defauts',
        'variable_xml',
    ];

    /**
     * Relation avec la table `mesures`
     */
    public function mesure()
    {
        return $this->belongsTo(Mesure::class, 'mesure_id');
    }

    /**
     * Relation avec la table `detail_commandes`
     */
    public function detailCommande()
    {
        return $this->belongsTo(DetailCommande::class, 'details_commande_id');
    }

}
