@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6">
    <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">

        {{-- Titre + Image --}}
        <div class="flex flex-col md:flex-row gap-6 items-start mb-10">
            @if ($modele->image)
                <img src="{{ asset('storage/' . $modele->image) }}" alt="{{ $modele->nom }}"
                     class="w-48 h-48 object-cover rounded-xl border">
            @endif
            <div class="flex-1 text-[16px]">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $modele->nom }}</h1>
                <p class="text-gray-700"><span class="font-semibold">Description :</span> {{ $modele->description }}</p>
                <p class="text-gray-700"><span class="font-semibold">Prix :</span> {{ number_format($modele->prix, 0, ',', ' ') }} DZD</p>
                <p class="text-gray-700"><span class="font-semibold">Catégorie :</span> {{ $modele->categorie->nom }}</p>
                <p class="text-gray-700"><span class="font-semibold">Type :</span> {{ $modele->type }}</p>
                <p class="text-gray-700"><span class="font-semibold">Sur mesure :</span>
                                    @if ($modele->sur_commande)
                                        <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                                    @else
                                        <i data-lucide="x-circle" class="w-5 h-5 text-red-500"></i>
                                    @endif
                </p>
            </div>

        {{-- Personnalisations --}}
        @if ($modele->attributValeurs->count())
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i data-lucide="sliders" class="w-5 h-5"></i> Attributs
                </h2>
                <div class="flex flex-wrap gap-2">
                    @foreach ($modele->attributValeurs as $valeur)
                        <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-800 text-sm px-3 py-1 rounded-full">
                            @if ($valeur->image)
                                <img src="{{ asset('storage/' . $valeur->image) }}" alt="{{ $valeur->nom }}"
                                     class="w-5 h-5 rounded-full object-cover">
                            @endif
                            <span>{{ $valeur->attribut->nom }} : {{ $valeur->nom }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        </div>

        {{-- Fichiers associés --}}
        <div class="mb-10 p-6 rounded-xl bg-[#F8F9FB]">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="folder-open" class="w-5 h-5"></i> Fichiers associés
            </h2>
            <ul class="space-y-3">
                @if ($modele->patron)
                    <li class="flex justify-between items-center">
                        <div class="flex items-center gap-2 text-gray-700">
                            <i data-lucide="ruler" class="w-5 h-5"></i> fichier Patron
                        </div>
                        <a href="{{ asset('storage/' . $modele->patron) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i data-lucide="download" class="w-4 h-4"></i> Télécharger
                        </a>
                    </li>
                @endif

                @if ($modele->xml)
                    <li class="flex justify-between items-center">
                        <div class="flex items-center gap-2 text-gray-700">
                            <i data-lucide="file-code" class="w-5 h-5"></i> Fichier de mesure
                        </div>
                        <a href="{{ asset('storage/' . $modele->xml) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <i data-lucide="download" class="w-4 h-4"></i> Télécharger
                        </a>
                    </li>
                @endif

                @if ($modele->autres_fichiers)
                    @foreach (json_decode($modele->autres_fichiers, true) as $fichier)
                        <li class="flex justify-between items-center">
                            <div class="flex items-center gap-2 text-gray-700">
                                <i data-lucide="paperclip" class="w-5 h-5"></i> {{ basename($fichier) }}
                            </div>
                            <a href="{{ asset('storage/' . $fichier) }}"
                               class="inline-flex items-left gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                                <i data-lucide="download" class="w-4 h-4"></i> Télécharger
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>


        {{-- Générer le patron --}}
        <div class="mb-10 justify-center">
            <form action="{{ route('patron.generate', $modele->id) }}" method="POST">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition">
                    <i data-lucide="wand-2" class="w-5 h-5"></i> Générer le Patron
                </button>
            </form>
        </div>


        {{-- Mesures associées --}}
        <div class="p-6 rounded-xl bg-[#F2F4F7]">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="ruler" class="w-5 h-5"></i> Mesures associées
            </h2>

            @if ($mesures->isEmpty())
                <p class="text-gray-600">Aucune mesure associée à ce modèle.</p>
            @else
                <div class="overflow-x-auto mb-4">
                    <table class="min-w-full bg-white rounded-lg overflow-hidden shadow text-sm">
                        <thead class="bg-gray-300 text-gray-700 font-semibold">
                            <tr>
                                <th class="px-6 py-3 text-left">Nom</th>
                                <th class="px-6 py-3 text-left">Valeur par défaut</th>
                                <th class="px-6 py-3 text-left">Variable XML</th>
                                <th class="px-6 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mesures as $mesure)
                                <tr class="border-b border-gray-200 hover:bg-gray-100 transition">
                                    <td class="px-6 py-4">{{ $mesure->label }}</td>
                                    <td class="px-6 py-4">{{ $mesure->valeur_par_defaut }}</td>
                                    <td class="px-6 py-4">{{ $mesure->variable_xml }}</td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <a href="{{ route('mesures.edit', $mesure) }}"
                                           class="flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition">
                                            <i data-lucide="pencil" class="w-4 h-4"></i> Modifier
                                        </a>
                                        <form action="{{ route('mesures.destroy', $mesure) }}" method="POST"
                                              onsubmit="return confirm('Confirmer la suppression ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition">
                                                <i data-lucide="trash" class="w-4 h-4"></i> Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Extraire mesures --}}
            <form action="{{ route('mesures.extract', $modele) }}" method="POST">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition">
                    <i data-lucide="file-search" class="w-5 h-5"></i> Extraire les mesures depuis la fiche
                </button>
            </form>
        </div>
            <div class="mt-8 flex flex-wrap justify-between gap-4">
            <a href="{{ route('modeles.index') }}"
                class="inline-flex items-center gap-2 bg-gray-500 text-white px-5 py-3 rounded-lg hover:bg-gray-600 transition">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                Retour à la commande
            </a>

            <a href="{{ route('modeles.edit', $modele) }}"
                class="inline-flex items-center gap-2 bg-yellow-500 text-white px-5 py-3 rounded-lg hover:bg-yellow-600 transition">
                <i data-lucide="pencil" class="w-5 h-5"></i>
                Modifier
            </a>
            </div>
    </div>


</div>

<script>
    lucide.createIcons();
</script>
@endsection
