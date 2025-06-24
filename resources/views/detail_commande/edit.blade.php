@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-xl mt-10 space-y-8">

    <div>
        <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
            <i data-lucide="pencil" class="w-6 h-6 text-indigo-600"></i>
            Modifier le détail de la commande
        </h2>
    </div>

    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 space-y-2">
        <div class="text-lg font-medium text-gray-800 flex items-center gap-2">
            <i data-lucide="package" class="w-5 h-5 text-gray-600"></i>
            Commande #{{ $detail_commande->commande->id }}
        </div>
        <p class="text-gray-700"><strong>Client :</strong> {{ $detail_commande->commande->user->name }}</p>
        <p class="text-gray-700"><strong>Modèle :</strong> {{ $detail_commande->modele->nom }}</p>
    </div>

    <form action="{{ route('detail_commande.update', $detail_commande->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Couturière -->
        <div>
            <label for="user_id" class="block text-gray-700 font-semibold mb-2">Couturière assignée</label>
            <select name="user_id" id="user_id"
                    class="border-gray-300 rounded-lg p-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-300 w-full">
                <option value="">-- Choisir une couturière --</option>
                @foreach($couturieres as $couturiere)
                    <option value="{{ $couturiere->id }}" {{ $detail_commande->user_id == $couturiere->id ? 'selected' : '' }}>
                        {{ $couturiere->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Statut -->
        <div>
            <label for="statut" class="block text-gray-700 font-semibold mb-2">Statut</label>
            <select name="statut" id="statut"
                    class="border-gray-300 rounded-lg p-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-300 w-full">
                <option value="Null" {{ $detail_commande->statut == 'Null' ? 'selected' : '' }}>Non défini</option>
                <option value="validee" {{ $detail_commande->statut == 'validee' ? 'selected' : '' }}>Validée</option>
                <option value="refuser" {{ $detail_commande->statut == 'refuser' ? 'selected' : '' }}>Refusée</option>
                <option value="fini" {{ $detail_commande->statut == 'fini' ? 'selected' : '' }}>Terminée</option>
            </select>
        </div>

        <!-- Quantité -->
        <div>
            <label for="quantite" class="block text-gray-700 font-semibold mb-2">Quantité</label>
            <input type="number" name="quantite" id="quantite" value="{{ $detail_commande->quantite }}" min="1"
                   class="w-full border-gray-300 rounded-lg p-3 shadow-sm focus:ring focus:ring-blue-300">
        </div>

        <!-- Prix unitaire -->
        <div>
            <label for="prix_unitaire" class="block text-gray-700 font-semibold mb-2">Prix unitaire</label>
            <input type="text" name="prix_unitaire" id="prix_unitaire" value="{{ $detail_commande->prix_unitaire }}"
                   class="w-full border-gray-300 rounded-lg p-3 shadow-sm focus:ring focus:ring-blue-300">
        </div>

        <!-- Personnalisation -->
        <div>
            <label for="custom" class="block text-gray-700 font-semibold mb-2">Personnalisation</label>
            <select name="custom" id="custom"
                    class="border-gray-300 rounded-lg p-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-300 w-full">
                <option value="0" {{ !$detail_commande->custom ? 'selected' : '' }}>Non</option>
                <option value="1" {{ $detail_commande->custom ? 'selected' : '' }}>Oui</option>
            </select>
        </div>

        <!-- Mesures -->
        @if($detail_commande->mesuresDetail->isNotEmpty())
        <div>
            <h3 class="text-2xl font-semibold text-gray-800 flex items-center gap-2 mb-4">
                <i data-lucide="ruler" class="w-5 h-5 text-gray-600"></i>
                Modifier les mesures
            </h3>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
               @foreach($detail_commande->mesuresDetail as $mesure)
    @if($mesure->mesure)
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">
                {{ $mesure->mesure->label }}
            </label>
            <input type="text" name="mesures[{{ $mesure->id }}]" value="{{ old('mesures.'.$mesure->id, $mesure->valeur_mesure) }}"
                   class="w-full border-gray-300 rounded-lg p-3 shadow-sm focus:ring focus:ring-blue-300">

            @error('mesures.' . $mesure->id)
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    @endif
@endforeach
            </div>
        </div>
        @endif

        <!-- Boutons -->
        <div class="flex justify-between mt-8">
            <a href="{{ route('commandes.detail_commande', $detail_commande->commande->id) }}"
               class="inline-flex items-center gap-2 bg-gray-500 text-white px-5 py-3 rounded-lg hover:bg-gray-600 transition">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                Retour
            </a>

            <button type="submit"
                    class="inline-flex items-center gap-2 bg-green-600 text-white px-5 py-3 rounded-lg hover:bg-green-700 transition">
                <i data-lucide="save" class="w-5 h-5"></i>
                Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection
