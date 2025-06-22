@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">

        {{-- SECTION : Commandes en cours --}}
        <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i data-lucide="package" class="w-6 h-6 text-gray-600"></i>
            Mes Commandes
        </h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 border border-green-300 rounded-lg px-4 py-3 mb-6 flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($commandesEnCours->isEmpty())
            <p class="text-center text-gray-500 text-lg mb-8">Aucune commande en cours.</p>
        @else
            <div class="overflow-x-auto mb-12">
                <table class="min-w-full divide-y divide-gray-200 text-base text-gray-800">
                    <thead class="bg-gray-200 text-gray-700 font-semibold">
                        <tr>
                            <th class="px-6 py-4 text-left">ID Détail</th>
                            <th class="px-6 py-4 text-left">Modèle</th>
                            <th class="px-6 py-4 text-left">Client</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($commandesEnCours as $commande)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">#{{ $commande->id }}</td>
                                <td class="px-6 py-4">{{ $commande->modele->nom ?? '—' }}</td>
                                <td class="px-6 py-4">{{ $commande->commande->user->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-3 flex-wrap">
                                        <a href="{{ route('commandes.details', $commande->id) }}"
                                            class="flex items-center gap-1 px-4 py-2 border border-blue-200 bg-blue-50 text-blue-700 rounded-md shadow-sm hover:bg-blue-100 transition">
                                            <i data-lucide="eye" class="w-4 h-4"></i> Voir
                                        </a>
                                        <form action="{{ route('couturiere.commandes.terminer', $commande->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="flex items-center gap-1 px-4 py-2 border border-green-200 bg-green-50 text-green-700 rounded-md shadow-sm hover:bg-green-100 transition">
                                                <i data-lucide="check" class="w-4 h-4"></i> Terminer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- SECTION : Commandes terminées --}}
        <h2 class="text-2xl font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <i data-lucide="check-square" class="w-5 h-5 text-gray-600"></i>
            Commandes terminées
        </h2>

        @if ($commandesTerminees->isEmpty())
            <p class="text-center text-gray-500 text-base">Aucune commande terminée.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-base text-gray-800">
                    <thead class="bg-gray-200 text-gray-700 font-semibold">
                        <tr>
                            <th class="px-6 py-4 text-left">ID Détail</th>
                            <th class="px-6 py-4 text-left">Modèle</th>
                            <th class="px-6 py-4 text-left">Client</th>
                            <th class="px-6 py-4 text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($commandesTerminees as $commande)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">#{{ $commande->id }}</td>
                                <td class="px-6 py-4">{{ $commande->modele->nom ?? '—' }}</td>
                                <td class="px-6 py-4">{{ $commande->commande->user->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-3 py-1 rounded-full text-white bg-green-600 text-sm font-medium">
                                        Terminée
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
</div>
@endsection
