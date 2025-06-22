@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-lg mt-8">

        <h2 class="text-3xl font-bold text-[#05335E] mb-6 flex items-center gap-2">
            <i data-lucide="shopping-bag" class="w-6 h-6 text-[#05335E]"></i>
            Commande #{{ $commande->id }}
        </h2>

        <div class="bg-gray-50 p-6 rounded-xl shadow-sm mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">

                <p class="flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-gray-500"></i>
                    <span class="font-semibold">Client :</span> {{ $commande->user->name }}
                </p>

                <p class="flex items-center gap-2">
                    <i data-lucide="calendar-days" class="w-5 h-5 text-gray-500"></i>
                    <span class="font-semibold">Date :</span> {{ $commande->created_at->format('d/m/Y') }}
                </p>

                <p class="flex items-center gap-2">
                    <i data-lucide="phone" class="w-5 h-5 text-gray-500"></i>
                    <span class="font-semibold">Téléphone :</span> {{ $commande->user->telephone ?? 'N/A' }}
                </p>
                 <p class="flex items-center gap-2">
                    <i data-lucide="house" class="w-5 h-5 text-gray-500"></i>
                    <span class="font-semibold">Adresse :</span> {{ $commande->user->adresse ?? 'N/A' }}
                </p>

                <p class="flex items-center gap-2">
                    <i data-lucide="credit-card" class="w-5 h-5 text-gray-500"></i>
                    <span class="font-semibold">Montant total :</span>
                    <span class="text-green-600 font-semibold">{{ number_format($commande->montant_total, 2) }} €</span>
                </p>

                <p class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5 text-gray-500"></i>
                    <span class="font-semibold">Statut :</span>
                    <span
                        class="px-3 py-1 rounded-full text-white text-sm font-semibold
                    {{ $commande->statut == 'validee'
                        ? 'bg-green-500'
                        : ($commande->statut == 'refusee'
                            ? 'bg-red-500'
                            : 'bg-yellow-500') }}">
                        {{ ucfirst($commande->statut) }}
                    </span>
                </p>

            </div>
        </div>

        <h3 class="text-2xl font-semibold text-[#05335E] mb-4 flex items-center gap-2">
            <i data-lucide="package" class="w-6 h-6 text-[#05335E]"></i>
            Produits commandés
        </h3>

        <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
            <ul class="divide-y divide-gray-200">
                @foreach ($commande->details as $detail)
                    <li class="py-4 flex items-center justify-between gap-4">
                        {{-- Image du modèle --}}
                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/' . $detail->modele->image) }}" alt="{{ $detail->modele->nom }}"
                                class="w-20 h-20 object-cover rounded-xl border border-gray-300">
                        </div>

                        {{-- Infos sur le produit --}}
                        <div class="flex-1">
                            <p class="text-lg font-semibold text-gray-800">{{ $detail->modele->nom }}</p>
                            <p class="text-sm text-gray-500">Quantité : x{{ $detail->quantite }}</p>
                        </div>

                        {{-- Actions / Prix / Couturière --}}
                        <div class="flex items-center gap-3">
                            <span class="text-gray-800 font-semibold">{{ number_format($detail->prix_unitaire, 2) }}
                                €</span>

                            @if ($detail->custom)
                                @if ($detail->couturiere)
                                    <span class="text-green-600 text-sm font-medium">
                                        Assignée à : <strong>{{ $detail->couturiere->name }}</strong>
                                    </span>
                                @else
                                    <a href="{{ route('commandes.detail_commande', $detail->id) }}"
                                        class="bg-purple-600 hover:bg-purple-700 text-white text-sm px-4 py-2 rounded-xl shadow transition flex items-center gap-1">
                                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                                        Assigner couturière
                                    </a>
                                @endif
                            @endif

                            <a href="{{ route('commandes.details', $detail->id) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-xl shadow transition flex items-center gap-1">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                                Voir détails
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('commandes.index') }}"
                class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-xl shadow transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Retour
            </a>
        </div>
    </div>

    {{-- Script Lucide --}}
    @push('scripts')
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        </script>
    @endpush
@endsection
