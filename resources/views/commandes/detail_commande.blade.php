@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg mt-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">🧵 Assigner une couturière</h2>

    <div class="bg-gray-100 p-6 rounded-lg shadow-sm">
        <p class="text-lg"><strong class="font-semibold">📦 Commande #{{ $detail_commande->commande->id }}</strong></p>
        <p class="text-lg"><strong class="font-semibold">👗 Modèle :</strong> {{ $detail_commande->modele->nom }}</p>
        <p class="text-lg"><strong class="font-semibold">Quantité :</strong> x{{ $detail_commande->quantite }}</p>
        <p class="text-lg"><strong class="font-semibold">Prix unitaire :</strong>
            <span class="text-green-600 font-semibold">{{ number_format($detail_commande->prix_unitaire, 2) }} €</span>
        </p>
    </div>

    <h4 class="text-2xl font-bold text-gray-700 mt-8">👩‍🎨 Sélectionner une couturière :</h4>
    <div class="bg-white p-6 rounded-lg shadow-sm mt-4">
        <form action="{{ route('commandes.assigner_couturiere', $detail_commande->id) }}" method="POST">
            @csrf
            <div class="flex items-center space-x-4">
                <select name="user_id" class="form-select border-gray-300 rounded-lg p-2 text-gray-700 shadow-sm focus:ring focus:ring-blue-300 w-full">
                    <option value="">-- Choisir une couturière --</option>
                    @foreach($couturieres as $couturiere)
                        <option value="{{ $couturiere->id }}" {{ $detail_commande->user_id == $couturiere->id ? 'selected' : '' }}>
                            {{ $couturiere->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                    ✅ Assigner
                </button>
            </div>
        </form>
    </div>

    <div class="mt-8">
        <a href="{{ route('commandes.show', $detail_commande->commande->id) }}"
           class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-300">
            🔙 Retour à la commande
        </a>
    </div>
</div>
@endsection
