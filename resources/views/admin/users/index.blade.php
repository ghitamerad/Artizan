@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-semibold text-gray-700 mb-5 text-center">Liste des Utilisateurs</h2>

    <div class="flex justify-between mb-4">
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Ajouter un Utilisateur</a>
    </div>

    <table class="w-full border-collapse border border-gray-200">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-3 text-left">ID</th>
                <th class="border p-3 text-left">Nom</th>
                <th class="border p-3 text-left">Email</th>
                <th class="border p-3 text-left">Rôle</th>
                <th class="border p-3 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="border p-3">{{ $user->id }}</td>
                    <td class="border p-3">{{ $user->name }}</td>
                    <td class="border p-3">{{ $user->email }}</td>
                    <td class="border p-3 capitalize">{{ $user->role }}</td>
                    <td class="border p-3 flex justify-center space-x-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Modifier</a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Supprimer cet utilisateur ?')" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
