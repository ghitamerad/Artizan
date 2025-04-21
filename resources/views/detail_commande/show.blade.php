@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg mt-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center gap-2">🧵 Assigner une couturière</h2>

    <div class="bg-gray-100 p-6 rounded-lg shadow-sm">
        <p class="text-lg font-semibold">📦 Commande #{{ $detail_commande->commande->id }}</p>
        <p class="text-lg"><strong class="font-semibold">👤 Client :</strong> {{ $detail_commande->commande->user->name }}</p>
        <p class="text-lg"><strong class="font-semibold">👗 Modèle :</strong> {{ $detail_commande->modele->nom }}</p>
        <p class="text-lg"><strong class="font-semibold">Quantité :</strong> x{{ $detail_commande->quantite }}</p>
        <p class="text-lg"><strong class="font-semibold">Prix unitaire :</strong>
            <span class="text-green-600 font-semibold">{{ number_format($detail_commande->prix_unitaire, 2) }} €</span>
        </p>
        <p class="text-lg"><strong class="font-semibold">🔧 Sur commande ? :</strong>
            <span class="{{ $detail_commande->custom ? 'text-blue-600' : 'text-gray-500' }}">
                {{ $detail_commande->custom ? 'Oui' : 'Non' }}
            </span>
        </p>
        <p class="text-lg flex items-center">
            <strong class="font-semibold">📌 Statut :</strong>
            <span class="ml-2 px-4 py-1 rounded-full text-white text-sm font-semibold
                {{ $detail_commande->statut == 'terminee' ? 'bg-green-500' :
                   ($detail_commande->statut == 'en cours' ? 'bg-yellow-500' : 'bg-gray-500') }}">
                {{ ucfirst($detail_commande->statut) }}
            </span>
        </p>
    </div>

    <div class="mt-6">
        <h2 class="text-xl font-bold text-gray-800 mb-3">📏 Mesures associées</h2>

        @if($detail_commande->custom && $detail_commande->mesuresDetail->isNotEmpty())

            <div class="overflow-x-auto">
                <table class="w-full border-collapse bg-gray-100 rounded-lg shadow-md">
                    <thead>
                        <tr class="bg-gray-300 text-gray-800">
                            <th class="px-6 py-3 text-left font-semibold">Nom de la mesure</th>
                            <th class="px-6 py-3 text-left font-semibold">Valeur</th>
                            <th class="px-6 py-3 text-left font-semibold">Valeur par défaut</th>
                            <th class="px-6 py-3 text-left font-semibold">Variable XML</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detail_commande->mesuresDetail as $mesure)
                        <tr class="border-b border-gray-200 hover:bg-gray-200 transition">
                                <td class="px-6 py-3 text-left font-semibold">{{ $mesure->mesure->label }}</td>
                                <td class="px-6 py-3 text-left font-semibold ">{{ $mesure->valeur_mesure }}</td>
                                <td class="px-6 py-3 text-left font-semibold ">{{ $mesure->valeur_par_defauts }}</td>
                                <td class="px-6 py-3 text-left font-semibold ">{{ $mesure->variable_xml }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Bouton Extraire les Mesures -->
        <div class="mt-4">
 <a href="{{ route('detail_commande.edit', $detail_commande->id) }}"
           class="bg-purple-500 text-white px-6 py-3 rounded-lg hover:bg-purple-700 ">
            ✏️ Modifier
        </a>

        </div>
    </div>
    <!-- 🔼🔼🔼 FIN TABLEAU DES MESURES 🔼🔼🔼 -->

    @if(Auth::check())
    @php
        $role = Auth::user()->role;
    @endphp
    @if(in_array($role, ['admin', 'gerante']))
    <h4 class="text-2xl font-bold text-gray-700 mt-8">👩‍🎨 Sélectionner une couturière :</h4>
    <div class="bg-white p-6 rounded-lg shadow-sm mt-4">
        <form action="{{ route('commandes.assigner_couturiere', $detail_commande->id) }}" method="POST">
            @csrf
            <div class="flex flex-col space-y-4">
                <select name="user_id" class="form-select border-gray-300 rounded-lg p-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-300 w-full">
                    <option value="">-- Choisir une couturière --</option>
                    @foreach($couturieres as $couturiere)
                        <option value="{{ $couturiere->id }}" {{ $detail_commande->user_id == $couturiere->id ? 'selected' : '' }}>
                            {{ $couturiere->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="bg-blue-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700 transition duration-300 shadow-md w-full">
                    ✅ Assigner
                </button>
            </div>
        </form>
    </div>
    @endif
    @endif

    @if($detail_commande->fichier_patron)
    <div class="mt-4 p-4 bg-gray-100 rounded-lg shadow-sm">
        <p class="text-lg font-semibold">📂 Patron généré :</p>
        <p class="text-gray-800">{{ basename($detail_commande->fichier_patron) }}</p>

        <a href="{{ route('patron.telecharger', $detail_commande->id) }}"
           class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300 shadow-md flex items-center gap-2 mt-2">
            ⬇️ Télécharger le patron
        </a>
    </div>
@endif


    <div class="mt-8 flex justify-between">
        <a href="{{ route('commandes.detail_commande', $detail_commande->id) }}"
           class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-300 shadow-md flex items-center gap-2">
            🔙 Retour à la commande
        </a>

        <a href="{{ route('detail_commande.edit', $detail_commande->id) }}"
           class="bg-yellow-500 text-white px-6 py-3 rounded-lg hover:bg-yellow-600 transition duration-300 shadow-md flex items-center gap-2">
            ✏️ Modifier
        </a>

        @if($detail_commande->fichier_patron == null)
        <a href="{{ route('patron.custom', $detail_commande->id) }}"class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-yellow-600 transition duration-300 shadow-md flex items-center gap-2">
            Générer un patron personnalisé
        </a>
        @endif

        @if($detail_commande->fichier_patron)
            <a href="{{ route('patron.custom.show', $detail_commande->id) }}" class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-yellow-600 transition duration-300 shadow-md flex items-center gap-2">
                Voir le patron personnalisé
            </a>
        @endif

    </div>
</div>
@endsection
