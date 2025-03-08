<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modele;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PanierController extends Controller
{
    public function ajouter($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $panier = Cache::get("panier_{$userId}", []);

        $modele = Modele::findOrFail($id);

        // Ajouter ou mettre à jour la quantité dans le panier
        if (isset($panier[$id])) {
            $panier[$id]['quantite']++;
        } else {
            $panier[$id] = [
                'id' => $modele->id,
                'nom' => $modele->nom,
                'prix' => $modele->prix,
                'quantite' => 1,
            ];
        }

        Cache::put("panier_{$userId}", $panier, now()->addHours(2));

        return redirect()->back()->with('message', 'Modèle ajouté au panier !');
    }
}
