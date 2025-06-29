@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-6">
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-8 shadow-sm">

            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Liste des Commandes</h1>

                <a href="{{ route('commandes.create') }}"
                    class="inline-flex items-center gap-2 bg-[#05335E] hover:bg-blue-800 text-white px-5 py-2 rounded-xl shadow transition">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>Créer une commande</span>
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 text-green-800 border border-green-300 rounded-lg px-4 py-3 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" x-data="{ openRow: null }">
                    <thead class="bg-gray-200 text-gray-700 text-base font-semibold">
                        <tr>
                            <th class="px-6 py-4 text-left">ID</th>
                            <th class="px-6 py-4 text-left">Client</th>
                            <th class="px-6 py-4 text-left">Montant Total (DZD)</th>
                            <th class="px-6 py-4 text-left">Statut</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-base text-gray-800">
                        @foreach ($commandes as $index => $commande)
                            <tr class="hover:bg-gray-50 transition cursor-pointer"
                                @click="window.location='{{ route('commandes.show', $commande) }}'">
                                <td class="px-6 py-4">{{ $commande->id }}</td>
                                <td class="px-6 py-4">{{ $commande->user->name }}</td>
                                <td class="px-6 py-4">{{ number_format($commande->montant_total, 2) }} DZD</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-white text-base
        @switch($commande->statut)
            @case('validee') bg-green-500 @break
            @case('refusee') bg-red-500 @break
            @case('annulee') bg-red-500 @break
            @case('assigner') bg-purple-600 @break
            @default bg-yellow-500
        @endswitch">
                                        {{ ucfirst($commande->statut) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex gap-3 flex-wrap items-center">

                                        <button
                                            @click.stop="openRow === {{ $index }} ? openRow = null : openRow = {{ $index }}"
                                            class="flex items-center gap-1 px-3 py-1 border border-blue-200 bg-blue-50 text-blue-700 rounded-md shadow-sm hover:bg-blue-100 transition">
                                            <i data-lucide="chevron-down" class="w-4 h-4"
                                                :class="{ 'rotate-180': openRow === {{ $index }} }"></i>
                                            <span>Détails</span>
                                        </button>

                                        @can('update', $commande)
                                            <a href="{{ route('commandes.edit', $commande) }}"
                                                class="flex items-center gap-1 px-3 py-1 border border-yellow-200 bg-yellow-50 text-yellow-700 rounded-md shadow-sm hover:bg-yellow-100 transition">
                                                <i data-lucide="pencil" class="w-4 h-4"></i> Modifier
                                            </a>
                                        @endcan

                                        @can('validateCommande', $commande)
                                            @if ($commande->statut === 'en_attente')
                                                <form action="{{ route('commandes.validate', $commande) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="flex items-center gap-1 px-3 py-1 border border-green-200 bg-green-50 text-green-700 rounded-md shadow-sm hover:bg-green-100 transition">
                                                        <i data-lucide="check" class="w-4 h-4"></i> Valider
                                                    </button>
                                                </form>

                                                <form action="{{ route('commandes.invalidate', $commande) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="flex items-center gap-1 px-3 py-1 border border-red-200 bg-red-50 text-red-700 rounded-md shadow-sm hover:bg-red-100 transition">
                                                        <i data-lucide="x" class="w-4 h-4"></i> Refuser
                                                    </button>
                                                </form>
                                            @elseif(in_array($commande->statut, ['assignee', 'validee']))
                                                <form action="{{ route('commandes.expediee', $commande) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="flex items-center gap-1 px-3 py-1 border border-green-200 bg-green-50 text-green-700 rounded-md shadow-sm hover:bg-green-100 transition">
                                                        <i data-lucide="truck" class="w-4 h-4"></i> Expédier
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan


                                        {{-- Suppression désactivée
                                        @can('delete', $commande)
                                            <form action="{{ route('commandes.destroy', $commande) }}" method="POST"
                                                onsubmit="return confirm('Supprimer cette commande ?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="flex items-center gap-1 px-3 py-1 border border-gray-200 bg-gray-50 text-gray-700 rounded-md shadow-sm hover:bg-gray-100 transition">
                                                    <i data-lucide="trash" class="w-4 h-4"></i> Supprimer
                                                </button>
                                            </form>
                                        @endcan --}}
                                    </div>
                                </td>
                            </tr>

                            <tr x-show="openRow === {{ $index }}" x-transition x-cloak class="bg-gray-50">
                                <td colspan="5" class="px-6 pb-4">
                                    <div class="border-l-4 border-blue-500 pl-4 mt-2">
                                        <h4 class="font-semibold text-gray-700 mb-2">Détails :</h4>
                                        @if ($commande->details->isNotEmpty())
                                            <div class="space-y-4">
                                                @foreach ($commande->details as $detail)
                                                    <div
                                                        class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                                                        <div class="flex items-start gap-3">
                                                            <i data-lucide="shirt" class="w-5 h-5 text-[#05335E] mt-1"></i>
                                                            <div>
                                                                <p class="text-gray-800 font-medium">
                                                                    Modèle :
                                                                    <a href="{{ route('commandes.detail_commande', $detail) }}"
                                                                        class="text-blue-600 hover:underline">
                                                                        {{ $detail->modele->nom ?? '—' }}
                                                                    </a>
                                                                </p>
                                                                <p class="text-sm text-gray-600">
                                                                    Quantité : {{ $detail->quantite }} &nbsp; | &nbsp;
                                                                    Prix unitaire :
                                                                    {{ number_format($detail->prix_unitaire, 2) }} DZD
                                                                </p>
                                                                <p class="text-sm text-gray-500 mt-1">
                                                                    Type :
                                                                    <span
                                                                        class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ $detail->custom ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                                        {{ $detail->custom ? 'Sur-mesure' : 'Prêt-à-porter' }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-gray-500 text-base">Aucun détail pour cette commande.</p>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
