@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg mt-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-4">📦 Commande #{{ $commande->id }}</h2>

    <div class="mb-4">
        <p class="text-lg"><strong class="font-semibold">👤 Client :</strong> {{ $commande->user->name }}</p>
        <p class="text-lg"><strong class="font-semibold">💰 Montant total :</strong>
            <span class="text-green-600 font-semibold">{{ number_format($commande->montant_total, 2) }} €</span>
        </p>
        <p class="text-lg flex items-center">
            <strong class="font-semibold">📌 Statut :</strong>
            <span class="ml-2 px-3 py-1 rounded-full text-white
                {{ $commande->statut == 'validee' ? 'bg-green-500' :
                   ($commande->statut == 'refusee' ? 'bg-red-500' : 'bg-yellow-500') }}">
                {{ ucfirst($commande->statut) }}
            </span>
        </p>
    </div>

    <h4 class="text-2xl font-bold text-gray-700 mt-6">🛍️ Produits commandés :</h4>
    <ul class="mt-3 bg-gray-100 p-4 rounded-lg shadow-inner">
        @foreach ($commande->details as $detail)
            <li class="border-b py-2 flex justify-between items-center">
                <span class="text-lg">{{ $detail->modele->nom }} (x{{ $detail->quantite }})</span>
                <span class="font-semibold">{{ number_format($detail->prix_unitaire, 2) }} €</span>
            </li>
        @endforeach
    </ul>

    <div class="mt-6">
        <a href="{{ route('commandes.index') }}"
           class="bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-600 transition duration-300">
            🔙 Retour
        </a>
    </div>
</div>
@endsection
