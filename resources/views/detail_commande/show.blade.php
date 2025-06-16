@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-xl mt-10 space-y-8">

        <div>
            <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                <i data-lucide="sewing-pin" class="w-6 h-6 text-indigo-600"></i>
                Detail Commande #{{ $detail_commande->id }}
            </h2>
        </div>

        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 space-y-3">
            <div class="text-lg font-medium text-gray-800 flex items-center gap-2">
                <i data-lucide="package" class="w-5 h-5 text-gray-600"></i>
                Commande #{{ $detail_commande->commande->id }}
            </div>
            <p class="text-gray-700"><strong>Client :</strong> {{ $detail_commande->commande->user->name }}</p>
            <p class="text-gray-700"><strong>Modèle :</strong> {{ $detail_commande->modele->nom }}</p>
            <p class="text-gray-700"><strong>Quantité :</strong> x{{ $detail_commande->quantite }}</p>
            <p class="text-gray-700"><strong>Prix unitaire :</strong>
                <span class="text-green-600 font-semibold">{{ number_format($detail_commande->prix_unitaire, 2) }}
                    DZD</span>
            </p>
            <p class="text-gray-700"><strong>Sur commande :</strong>
                <span class="{{ $detail_commande->custom ? 'text-blue-600' : 'text-gray-500' }}">
                    {{ $detail_commande->custom ? 'Oui' : 'Non' }}
                </span>
            </p>
            <div class="flex items-center gap-2">
                <strong>Statut :</strong>
                <span
                    class="px-3 py-1 rounded-full text-white text-sm font-semibold
                {{ $detail_commande->statut == 'fini'
                    ? 'bg-green-500'
                    : ($detail_commande->statut == 'validee'
                        ? 'bg-yellow-500'
                        : 'bg-gray-500') }}">
                    {{ ucfirst($detail_commande->statut) }}
                </span>
            </div>
        </div>

        @if ($detail_commande->custom)

            @if (Auth::check() && in_array(Auth::user()->role, ['admin', 'gerante']))
                <div>
                    <h4 class="text-2xl font-semibold text-gray-700 flex items-center gap-2">
                        <i data-lucide="user-cog" class="w-5 h-5 text-gray-600"></i>
                        Sélectionner une couturière
                    </h4>

                    <div class="bg-white p-6 rounded-xl shadow-sm mt-4">
                        <form action="{{ route('commandes.assigner_couturiere', $detail_commande->id) }}" method="POST">
                            @csrf
                            <div class="flex flex-col gap-4">
                                <select name="user_id"
                                    class="border-gray-300 rounded-lg p-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-300 w-full">
                                    <option value="">-- Choisir une couturière --</option>
                                    @foreach ($couturieres as $couturiere)
                                        <option value="{{ $couturiere->id }}"
                                            {{ $detail_commande->user_id == $couturiere->id ? 'selected' : '' }}>
                                            {{ $couturiere->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <button type="submit"
                                    class="bg-blue-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700 transition duration-300 shadow-md">
                                    Assigner
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            @if ($detail_commande->fichier_patron)
                <div class="bg-gray-100 p-4 rounded-xl space-y-2">
                    <div class="flex items-center gap-2 text-lg font-medium text-gray-800">
                        <i data-lucide="folder" class="w-5 h-5 text-gray-600"></i>
                        Patron généré :
                    </div>
                    <p class="text-gray-700">{{ basename($detail_commande->fichier_patron) }}</p>
                    <a href="{{ route('patron.telecharger', $detail_commande->id) }}"
                        class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition">
                        <i data-lucide="download" class="w-5 h-5"></i>
                        Télécharger le patron
                    </a>
                </div>
            @endif

            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i data-lucide="ruler" class="w-5 h-5 text-gray-600"></i>
                    Mesures associées
                </h2>

                @if ($detail_commande->custom && $detail_commande->mesuresDetail->isNotEmpty())
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="w-full bg-white text-sm text-gray-800 rounded-xl">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-3 font-semibold">Nom de la mesure</th>
                                    <th class="text-left px-6 py-3 font-semibold">Valeur</th>
                                    <th class="text-left px-6 py-3 font-semibold">Par défaut</th>
                                    <th class="text-left px-6 py-3 font-semibold">Variable XML</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detail_commande->mesuresDetail as $mesure)
                                    <tr class="hover:bg-gray-50 border-t">
                                        <td class="px-6 py-3">{{ $mesure->mesure->label }}</td>
                                        <td class="px-6 py-3">{{ $mesure->valeur_mesure }}</td>
                                        <td class="px-6 py-3">{{ $mesure->valeur_par_defauts }}</td>
                                        <td class="px-6 py-3">{{ $mesure->variable_xml }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('detail_commande.edit', $detail_commande->id) }}"
                        class="inline-flex items-center gap-2 bg-purple-600 text-white px-5 py-2.5 rounded-lg hover:bg-purple-700 transition">
                        <i data-lucide="pencil" class="w-5 h-5"></i>
                        Modifier
                    </a>
                </div>
            </div>
        @endif

        <div class="mt-8 flex flex-wrap justify-between gap-4">
            <a href="{{ route('commandes.detail_commande', $detail_commande->id) }}"
                class="inline-flex items-center gap-2 bg-gray-500 text-white px-5 py-3 rounded-lg hover:bg-gray-600 transition">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                Retour à la commande
            </a>

            <a href="{{ route('detail_commande.edit', $detail_commande->id) }}"
                class="inline-flex items-center gap-2 bg-yellow-500 text-white px-5 py-3 rounded-lg hover:bg-yellow-600 transition">
                <i data-lucide="pencil" class="w-5 h-5"></i>
                Modifier
            </a>

            @if ($detail_commande->custom)
                @if ($detail_commande->fichier_patron == null)
                    <a href="{{ route('patron.custom', $detail_commande->id) }}"
                        class="inline-flex items-center gap-2 bg-green-600 text-white px-5 py-3 rounded-lg hover:bg-green-700 transition">
                        <i data-lucide="crop" class="w-5 h-5"></i>
                        Générer un patron personnalisé
                    </a>
                @endif

                @if ($detail_commande->fichier_patron)
                    <a href="{{ route('patron.custom.show', $detail_commande->id) }}"
                        class="inline-flex items-center gap-2 bg-orange-500 text-white px-5 py-3 rounded-lg hover:bg-orange-600 transition">
                        <i data-lucide="eye" class="w-5 h-5"></i>
                        Voir le patron personnalisé
                    </a>
                @endif
            @endif
        </div>
    </div>
@endsection
