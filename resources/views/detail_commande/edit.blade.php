@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg mt-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">✏️ Modifier le détail de la commande</h2>

    <div class="bg-gray-100 p-6 rounded-lg shadow-sm">
        <p class="text-lg font-semibold">📦 Commande #{{ $detail_commande->commande->id }}</p>
        <p class="text-lg"><strong class="font-semibold">👤 Client :</strong> {{ $detail_commande->commande->user->name }}</p>
        <p class="text-lg"><strong class="font-semibold">👗 Modèle :</strong> {{ $detail_commande->modele->nom }}</p>
    </div>

    <form action="{{ route('detail_commande.update', $detail_commande->id) }}" method="POST" class="mt-6">
        @csrf
        @method('PUT')

        <!-- Couturière -->
        <div class="mb-4">
            <label for="user_id" class="block text-gray-700 font-semibold mb-2">👩‍🎨 Couturière assignée</label>
            <select name="user_id" id="user_id" class="form-select w-full border-gray-300 rounded-lg p-3 shadow-sm focus:ring focus:ring-blue-300">
                <option value="">-- Choisir une couturière --</option>
                @foreach($couturieres as $couturiere)
                    <option value="{{ $couturiere->id }}" {{ $detail_commande->user_id == $couturiere->id ? 'selected' : '' }}>
                        {{ $couturiere->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Statut -->
        <div class="mb-4">
            <label for="statut" class="block text-gray-700 font-semibold mb-2">📌 Statut</label>
            <select name="statut" id="statut" class="form-select w-full border-gray-300 rounded-lg p-3 shadow-sm focus:ring focus:ring-blue-300">
                <option value="Null" {{ $detail_commande->statut == 'Null' ? 'selected' : '' }}>Non défini</option>
                <option value="validee" {{ $detail_commande->statut == 'validee' ? 'selected' : '' }}>Validée</option>
                <option value="refuser" {{ $detail_commande->statut == 'refuser' ? 'selected' : '' }}>Refusée</option>
                <option value="fini" {{ $detail_commande->statut == 'fini' ? 'selected' : '' }}>Terminée</option>
            </select>
        </div>

        <!-- Quantité -->
        <div class="mb-4">
            <label for="quantite" class="block text-gray-700 font-semibold mb-2">📦 Quantité</label>
            <input type="number" name="quantite" id="quantite" value="{{ $detail_commande->quantite }}" min="1"
                   class="w-full border-gray-300 rounded-lg p-3 shadow-sm focus:ring focus:ring-blue-300">
        </div>

        <!-- Prix unitaire -->
        <div class="mb-4">
            <label for="prix_unitaire" class="block text-gray-700 font-semibold mb-2">💰 Prix unitaire (€)</label>
            <input type="text" name="prix_unitaire" id="prix_unitaire" value="{{ $detail_commande->prix_unitaire }}"
                   class="w-full border-gray-300 rounded-lg p-3 shadow-sm focus:ring focus:ring-blue-300">
        </div>

        <!-- Custom -->
        <div class="mb-4">
            <label for="custom" class="text-gray-700 font-semibold">🔧 Personnalisation</label>
            <select name="custom" id="custom" class="form-select border-gray-300 rounded-lg p-2 text-gray-700 shadow-sm focus:ring focus:ring-blue-300 w-full">
                <option value="0" {{ !$detail_commande->custom ? 'selected' : '' }}>Non</option>
                <option value="1" {{ $detail_commande->custom ? 'selected' : '' }}>Oui</option>
            </select>
        </div>

        <!-- Section des mesures (avec vérification) -->
        @if($detail_commande->mesuresDetail->isNotEmpty())
        <h3 class="text-2xl font-bold text-gray-700 mt-8">📏 Modifier les mesures</h3>
        <div class="bg-white p-6 rounded-lg shadow-sm mt-4">
            @foreach($detail_commande->mesuresDetail as $mesure)
                @if($mesure->mesure) <!-- Vérifie que la relation existe -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">{{ $mesure->mesure->label }} :</label>
                        <input type="text" name="mesures[{{ $mesure->id }}]" value="{{ $mesure->valeur_mesure }}"
                            class="form-input border-gray-300 rounded-lg p-3 w-full shadow-sm focus:ring focus:ring-blue-300">
                    </div>
                @endif
            @endforeach
        </div>
    @endif


        <!-- Boutons -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('commandes.detail_commande', $detail_commande->commande->id) }}"
               class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-300 shadow-md flex items-center gap-2">
                🔙 Retour
            </a>

            <button type="submit"
                    class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-300 shadow-md">
                💾 Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection
