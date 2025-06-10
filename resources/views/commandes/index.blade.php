@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg mt-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Liste des commandes</h2>

            <a href="{{ route('commandes.create') }}"
                class="bg-blue-500 text-white px-5 py-2 rounded-lg hover:bg-blue-600 transition duration-300 flex items-center space-x-2">
                <i data-lucide="plus" class="w-5 h-5 text-white"></i>
                <span>Créer une commande</span>
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full border-collapse bg-gray-100 rounded-lg shadow-md" x-data="{ openRow: null }">
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
                    @foreach ($commandes as $index => $commande)
                        <tr class="border-b border-gray-200 hover:bg-gray-200 transition"
                            @click="window.location='{{ route('commandes.show', $commande) }}'">
                            <td class="px-6 py-4">{{ $commande->id }}</td>
                            <td class="px-6 py-4">{{ $commande->user->name }}</td>
                            <td class="px-6 py-4">{{ number_format($commande->montant_total, 2) }} €</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 rounded-full text-white
                                {{ $commande->statut == 'validee'
                                    ? 'bg-green-500'
                                    : ($commande->statut == 'refusee'
                                        ? 'bg-red-500'
                                        : 'bg-yellow-500') }}">
                                    {{ ucfirst($commande->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 flex flex-wrap gap-2 items-center">
                                <button
                                    @click="openRow === {{ $index }} ? openRow = null : openRow = {{ $index }}"
                                    class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 flex items-center space-x-1">
                                    <i data-lucide="chevron-down" class="w-4 h-4"
                                        :class="{ 'rotate-180': openRow === {{ $index }} }"></i>
                                    <span>Détails</span>
                                </button>

                                @can('update', $commande)
                                    <a href="{{ route('commandes.edit', $commande) }}"
                                        class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 flex items-center space-x-1">
                                        <i data-lucide="pencil" class="w-4 h-4 text-white"></i>
                                        <span>Modifier</span>
                                    </a>
                                @endcan

                                @can('validateCommande', $commande)
                                    <form action="{{ route('commandes.validate', $commande) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600 flex items-center space-x-1">
                                            <i data-lucide="check" class="w-4 h-4 text-white"></i>
                                            <span>Valider</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('commandes.invalidate', $commande) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 flex items-center space-x-1">
                                            <i data-lucide="x" class="w-4 h-4 text-white"></i>
                                            <span>Refuser</span>
                                        </button>
                                    </form>
                                @endcan

                                {{-- @can('delete', $commande)
                                <form action="{{ route('commandes.destroy', $commande) }}" method="POST"
                                      onsubmit="return confirm('Supprimer cette commande ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="bg-gray-500 text-white px-3 py-1 rounded-lg hover:bg-gray-600 flex items-center space-x-1">
                                        <i data-lucide="trash" class="w-4 h-4 text-white"></i>
                                        <span>Supprimer</span>
                                    </button>
                                </form>
                            @endcan --}}
                            </td>
                        </tr>

                        <tr x-show="openRow === {{ $index }}" x-transition x-cloak class="bg-white">
                            <td colspan="5" class="px-6 pb-4">
                                <div class="border-l-4 border-blue-500 pl-4 mt-2">
                                    <h4 class="font-semibold text-gray-700 mb-2">Détails :</h4>
                                    @if ($commande->details->isNotEmpty())
                                        <ul class="list-disc list-inside text-gray-600 space-y-1">
                                            @foreach ($commande->details as $detail)
                                                <li>
                                                    <a href="{{ route('commandes.detail_commande', $detail) }}"
                                                        class="hover:underline">
                                                        Modèle : {{ $detail->modele->nom ?? '—' }},
                                                        Quantité : {{ $detail->quantite }},
                                                        Prix : {{ number_format($detail->prix_unitaire, 2) }} DZD
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-gray-500 text-sm">Aucun détail pour cette commande.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
