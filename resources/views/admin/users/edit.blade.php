@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-lg">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Modifier un Utilisateur</h2>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Champ Nom -->
        <div>
            <label for="name" class="block text-gray-700 font-medium mb-1">Nom :</label>
            <input type="text" name="name" id="name" value="{{ $user->name }}" required
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <!-- Champ Email -->
        <div>
            <label for="email" class="block text-gray-700 font-medium mb-1">Email :</label>
            <input type="email" name="email" id="email" value="{{ $user->email }}" required
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <!-- Sélection du rôle -->
        <div>
            <label for="role" class="block text-gray-700 font-medium mb-1">Rôle :</label>
            <select name="role" id="role" required
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="client" {{ $user->role == 'client' ? 'selected' : '' }}>Client</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrateur</option>
                <option value="gerante" {{ $user->role == 'gerante' ? 'selected' : '' }}>Gérante</option>
                <option value="couturiere" {{ $user->role == 'couturiere' ? 'selected' : '' }}>Couturière</option>
            </select>
        </div>

        <!-- Boutons -->
        <div class="flex justify-between items-center mt-6">
            <button type="submit"
                class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition">
                Enregistrer
            </button>
            <a href="{{ route('admin.users.index') }}"
                class="px-5 py-2 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 transition">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
