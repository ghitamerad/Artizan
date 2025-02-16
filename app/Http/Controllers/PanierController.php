<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panier;
use App\Models\Modele;
use Illuminate\Support\Facades\Auth;

class PanierController extends Controller
{
    public function ajouter($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $modele = Modele::findOrFail($id);

        $panier = Panier::firstOrCreate(
            ['user_id' => Auth::id(), 'modele_id' => $modele->id],
            ['quantite' => 1]
        );

        if (!$panier->wasRecentlyCreated) {
            $panier->increment('quantite');
        }

        return redirect()->back()->with('message', 'Modèle ajouté au panier !');
    }
}
