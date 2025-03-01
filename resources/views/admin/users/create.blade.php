@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-semibold text-gray-700 mb-5 text-center">Ajouter un Utilisateur</h2>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-medium">Nom :</label>
            <input type="text" name="name" id="name" required class="w-full p-2 border rounded-lg" value="{{ old('name') }}">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-medium">Email :</label>
            <input type="email" name="email" id="email" required class="w-full p-2 border rounded-lg" value="{{ old('email') }}">
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-gray-700 font-medium">Numéro de téléphone :</label>
            <input type="text" name="phone" id="phone" required class="w-full p-2 border rounded-lg" value="{{ old('phone') }}">
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-medium">Mot de passe :</label>
            <input type="password" name="password" id="password" required class="w-full p-2 border rounded-lg">
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700 font-medium">Confirmer le mot de passe :</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full p-2 border rounded-lg">
        </div>

        <div class="mb-4">
            <label for="role" class="block text-gray-700 font-medium">Rôle :</label>
            <select name="role" id="role" class="w-full p-2 border rounded-lg">
                <option value="client">Client</option>
                <option value="admin">Administrateur</option>
                <option value="gerante">Gérante</option>
                <option value="couturiere">Couturière</option>
            </select>
        </div>

        <div class="flex justify-between">
            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Ajouter</button>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Annuler</a>
        </div>
    </form>
</div>
@endsection
