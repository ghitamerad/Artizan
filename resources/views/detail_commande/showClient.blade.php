@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">📄 Détails de la commande #{{ $commande->id }}</h2>

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <p class="text-gray-600">Date : <span class="font-semibold">{{ $commande->created_at->format('d/m/Y') }}</span></p>
        <p class="text-gray-600">Statut :
            <span class="px-2 py-1 text-white text-xs rounded-full
                {{ $commande->statut == 'en_attente' ? 'bg-yellow-500' : ($commande->statut == 'validee' ? 'bg-green-500' : 'bg-gray-500') }}">
                {{ ucfirst($commande->statut) }}
            </span>
        </p>
        <p class="text-gray-600">Montant total : <span class="font-semibold">{{ number_format($commande->montant_total, 2) }} €</span></p>
    </div>

    <h3 class="text-lg font-semibold text-gray-800 mb-3">🛍 Articles commandés</h3>
    <div class="bg-white p-6 rounded-lg shadow-md">
        @foreach ($detailsCommande as $detail)
            <div class="p-4 border rounded-lg mb-3">
                <p class="font-medium">Modèle #{{ $detail->modele_id }}</p>
                <p class="text-sm text-gray-500">Quantité : {{ $detail->quantite }}</p>
                <p class="text-sm text-gray-500">Prix unitaire : {{ number_format($detail->prix_unitaire, 2) }} €</p>
                <p class="text-sm text-gray-500">Customisé :
                    <span class="font-semibold {{ $detail->custom ? 'text-green-600' : 'text-gray-600' }}">
                        {{ $detail->custom ? 'Oui' : 'Non' }}
                    </span>
                </p>
            </div>
        @endforeach
    </div>

    <a href="{{ route('detail-commandes.index') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
        Retour à mes commandes
    </a>
</div>
@endsection
