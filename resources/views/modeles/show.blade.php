@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $modele->nom }}</h1>

<!-- Description + Attributs -->
<div class="mb-6 p-4 bg-gray-100 rounded-lg">
    <p><span class="font-semibold">Description :</span> {{ $modele->description }}</p>
    <p><span class="font-semibold">Prix :</span> {{ $modele->prix }} DZD</p>
    <p><span class="font-semibold">Catégorie :</span> {{ $modele->categorie->nom }}</p>
    <p><span class="font-semibold">Catégorie :</span> {{ $modele->type }}</p>

    @if ($modele->attributValeurs->count())
    <div class="mt-4">
        <span class="font-semibold">Personnalisations :</span>
        <div class="flex flex-wrap gap-2 mt-2">
            @foreach ($modele->attributValeurs as $valeur)
                <div class="inline-flex items-center space-x-2 bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
                    @if ($valeur->image)
                        <img src="{{ asset('storage/' . $valeur->image) }}" alt="{{ $valeur->nom }}" class="w-5 h-5 rounded-full object-cover">
                    @endif
                    <span>{{ $valeur->attribut->nom }} : {{ $valeur->nom }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endif

</div>


        <!-- Bouton Générer le Patron -->
        <div class="mt-4">
            <form action="{{ route('patron.generate', $modele->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                    ✨ Générer le Patron
                </button>
            </form>
        </div>

        <!-- Section des fichiers associés -->
        <div class="mt-6 bg-gray-100 p-4 rounded-lg">
            <h2 class="text-xl font-bold text-gray-800 mb-3">📂 Fichiers associés</h2>

            <ul class="space-y-2">
                @if ($modele->patron)
                    <li class="flex justify-between items-center bg-white p-2 rounded shadow">
                        <span>📐 Patron</span>
                        <a href="{{ asset('storage/' . $modele->patron) }}"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                            📥 Télécharger
                        </a>
                    </li>
                @endif

                @if ($modele->xml)
                    <li class="flex justify-between items-center bg-white p-2 rounded shadow">
                        <span>📜 Fichier XML</span>
                        <a href="{{ asset('storage/' . $modele->xml) }}"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                            📥 Télécharger
                        </a>
                    </li>
                @endif

                @if ($modele->autres_fichiers)
                    @foreach (json_decode($modele->autres_fichiers, true) as $fichier)
                        <li class="flex justify-between items-center bg-white p-2 rounded shadow">
                            <span>📎 {{ basename($fichier) }}</span>
                            <a href="{{ asset('storage/' . $fichier) }}"
                                class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                                📥 Télécharger
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>



        <div class="mt-6">
            <h2 class="text-xl font-bold text-gray-800 mb-3">📏 Mesures associées</h2>

            @if ($mesures->isEmpty())
                <p class="text-gray-600">Aucune mesure associée à ce modèle.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse bg-gray-100 rounded-lg shadow-md">
                        <thead>
                            <tr class="bg-gray-300 text-gray-800">
                                <th class="px-6 py-3 text-left font-semibold">Nom</th>
                                <th class="px-6 py-3 text-left font-semibold">Valeur par défaut</th>
                                <th class="px-6 py-3 text-left font-semibold">Variable XML</th>
                                <th class="px-6 py-3 text-left font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mesures as $mesure)
                                <tr class="border-b border-gray-200 hover:bg-gray-200 transition">
                                    <td class="px-6 py-4">{{ $mesure->label }}</td>
                                    <td class="px-6 py-4">{{ $mesure->valeur_par_defaut }}</td>
                                    <td class="px-6 py-4">{{ $mesure->variable_xml }}</td>
                                    <td class="px-6 py-4 flex space-x-2">
                                        <a href="{{ route('mesures.edit', $mesure) }}"
                                            class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                                            ✏️ Modifier
                                        </a>
                                        <form action="{{ route('mesures.destroy', $mesure) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition"
                                                onclick="return confirm('Confirmer la suppression ?')">
                                                ❌ Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Bouton Extraire les Mesures -->
            <div class="mt-4">
                <form action="{{ route('mesures.extract', $modele) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition">
                        🛠️ Extraire les mesures depuis XML
                    </button>
                </form>
            </div>
        </div>

    </div>
@endsection
