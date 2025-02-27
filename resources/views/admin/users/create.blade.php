@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-center text-gray-700 mb-5">Ajouter un Utilisateur</h2>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-medium">Nom :</label>
            <input type="text" name="name" required class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-medium">Email :</label>
            <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-medium">Mot de passe :</label>
            <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700 font-medium">Confirmer le mot de passe :</label>
            <input type="password" name="password_confirmation" required class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
        </div>

        <div class="mb-4">
            <label for="role" class="block text-gray-700 font-medium">Rôle :</label>
            <select name="role" required class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                <option value="client">Client</option>
                <option value="admin">Administrateur</option>
                <option value="gerante">Gérante</option>
                <option value="couturiere">Couturière</option>
            </select>
        </div>

        <div class="text-center">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Ajouter
            </button>
        </div>
    </form>
</div>
@endsection
