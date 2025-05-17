@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Liste des Devis</h1>
        <a href="{{ route('devis.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            + Nouveau Devis
        </a>
    </div>

    @if (session('message'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white shadow rounded">
        <table class="w-full table-auto text-left">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">Client</th>
                    <th class="p-3">Description</th>
                    <th class="p-3">Catégorie</th>
                    <th class="p-3">Image</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($devis as $devi)
                    <tr class="border-b">
                        <td class="p-3">{{ $devi->id }}</td>
                        <td class="p-3">{{ $devi->utilisateur->name }}</td>
                        <td class="p-3">{{ $devi->description }}</td>
                        <td class="p-3">{{ $devi->categorie->nom ?? '-' }}</td>
                        <td class="p-3">
                            @if ($devi->image)
                                <img src="{{ asset('storage/' . $devi->image) }}" alt="Image" class="w-16 h-16 object-cover rounded">
                            @else
                                -
                            @endif
                        </td>
                        <td class="p-3 flex space-x-2">
                            <a href="{{ route('devis.show', $devi) }}" class="text-blue-600 hover:underline">Voir</a>
                            <a href="{{ route('devis.edit', $devi) }}" class="text-yellow-600 hover:underline">Modifier</a>
                            <form action="{{ route('devis.destroy', $devi) }}" method="POST" onsubmit="return confirm('Supprimer ce devis ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">Aucun devis trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
