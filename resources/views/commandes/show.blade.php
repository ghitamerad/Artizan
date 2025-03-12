@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow-lg mt-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">📦 Commande #{{ $commande->id }}</h2>

    <div class="bg-gray-100 p-6 rounded-lg shadow-sm">
        <p class="text-lg"><strong class="font-semibold">👤 Client :</strong> {{ $commande->user->name }}</p>
        <p class="text-lg"><strong class="font-semibold">📅 Date :</strong> {{ $commande->created_at->format('d/m/Y') }}</p>
        <p class="text-lg"><strong class="font-semibold">💰 Montant total :</strong>
            <span class="text-green-600 font-semibold">{{ number_format($commande->montant_total, 2) }} €</span>
        </p>
        <p class="text-lg flex items-center mt-3">
            <strong class="font-semibold">📌 Statut :</strong>
            <span class="ml-2 px-4 py-1 rounded-full text-white text-sm font-semibold
                {{ $commande->statut == 'validee' ? 'bg-green-500' :
                   ($commande->statut == 'refusee' ? 'bg-red-500' : 'bg-yellow-500') }} ">
                {{ ucfirst($commande->statut) }}
            </span>
        </p>
    </div>

    <h4 class="text-2xl font-bold text-gray-700 mt-8">🛍️ Produits commandés :</h4>
    <div class="bg-gray-50 p-6 rounded-lg shadow-sm mt-4">
        <ul>
            @foreach ($commande->details as $detail)
                <li class="border-b last:border-b-0 py-3 flex justify-between items-center">
                    <div>
                        <span class="text-lg font-semibold text-gray-800">{{ $detail->modele->nom }}</span>
                        <p class="text-sm text-gray-500">Quantité : x{{ $detail->quantite }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="font-semibold text-gray-800">{{ number_format($detail->prix_unitaire, 2) }} €</span>

                        @if ($detail->custom)
                            <a href="{{ route('commandes.detail_commande', $detail->id) }}"
                               class="flex items-center gap-1 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-300 shadow-md">
                                ✏️ Assigner Couturière
                            </a>
                        @endif

                        <a href="{{ route('commandes.details', $detail->id) }}"
                           class="flex items-center gap-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 shadow-md">
                            🔍 Voir Détails
                        </a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('commandes.index') }}"
           class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-300 shadow-md inline-flex items-center gap-2">
            🔙 Retour
        </a>
    </div>
</div>
@endsection
