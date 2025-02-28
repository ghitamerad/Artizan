@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg mt-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">📜 Liste des commandes</h2>

        <!-- Bouton Créer une commande -->
        <a href="{{ route('commandes.create') }}"
           class="bg-blue-500 text-white px-5 py-2 rounded-lg hover:bg-blue-600 transition duration-300">
            ➕ Créer une commande
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full border-collapse bg-gray-100 rounded-lg shadow-md">
            <thead>
                <tr class="bg-gray-300 text-gray-800">
                    <th class="px-6 py-3 text-left font-semibold">ID</th>
                    <th class="px-6 py-3 text-left font-semibold">Client</th>
                    <th class="px-6 py-3 text-left font-semibold">Montant Total (€)</th>
                    <th class="px-6 py-3 text-left font-semibold">Statut</th>
                    <th class="px-6 py-3 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($commandes as $commande)
                    <tr class="border-b border-gray-200 hover:bg-gray-200 transition">
                        <td class="px-6 py-4">{{ $commande->id }}</td>
                        <td class="px-6 py-4">{{ $commande->user->name }}</td>
                        <td class="px-6 py-4">{{ number_format($commande->montant_total, 2) }} €</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-white
                                {{ $commande->statut == 'validee' ? 'bg-green-500' :
                                   ($commande->statut == 'refusee' ? 'bg-red-500' : 'bg-yellow-500') }}">
                                {{ ucfirst($commande->statut) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex space-x-2">
                            <a href="{{ route('commandes.show', $commande) }}"
                               class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                                👁️ Voir
                            </a>

                            @can('update', $commande)
                                <a href="{{ route('commandes.edit', $commande) }}"
                                   class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                                    ✏️ Modifier
                                </a>
                            @endcan

                            @can('validateCommande', $commande)
                                <form action="{{ route('commandes.validate', $commande) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                        ✅ Valider
                                    </button>
                                </form>
                                <form action="{{ route('commandes.invalidate', $commande) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                        ❌ Refuser
                                    </button>
                                </form>
                            @endcan

                            @can('delete', $commande)
                                <form action="{{ route('commandes.destroy', $commande) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition"
                                            onclick="return confirm('Supprimer cette commande ?')">
                                        🗑️ Supprimer
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
