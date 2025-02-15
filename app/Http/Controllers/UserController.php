<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate; // Ajoute cette ligne
use App\Policies\UserPolicy;
use App\Providers\AuthServiceProvider;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Affiche le formulaire d'ajout d'un utilisateur
    public function create()
    {
        $this->authorize('create', User::class);
        return view('admin.users.create');
    }

    /**
     * Modifier un utilisateur.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('admin.users.edit', compact('user'));
    }

    public function store(Request $request)
{
    $this->authorize('create', User::class);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|in:admin,gerante,couturiere,client', // Mise à jour des rôles
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password,
        'role' => $request->role, // Enregistrement du rôle correct
    ]);

    return redirect()->route('admin.users.index')->with('success', 'Utilisateur ajouté avec succès.');
}


public function update(Request $request, User $user)
{
    $this->authorize('update', $user); // Vérification des permissions

    // Validation des données
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'role' => 'required|in:admin,gerante,couturiere,client', // Vérifie que le rôle est valide
    ]);

    // Mise à jour de l'utilisateur
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role, // Mise à jour du rôle
    ]);

    // Redirection avec un message de succès
    return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès.');
}

    /**
     * Supprimer un utilisateur.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
}
