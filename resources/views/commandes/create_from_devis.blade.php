@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-md mt-10">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Créer une commande à partir du devis</h2>

        <form action="{{ route('devis.store_commande', $devis->id) }}" method="POST" class="space-y-6" x-data="{ custom: true }">
            @csrf

            <div>
                <label class="font-semibold text-gray-700">Client :</label>
                <p class="text-gray-800">{{ $client->name }}</p>
            </div>

            <div>
                <label class="font-semibold text-gray-700">Modèle :</label>
                <p class="text-gray-800">{{ $modele->nom }}</p>
            </div>

            <div>
                <label for="quantite" class="block font-semibold text-gray-700">Quantité</label>
                <input type="number" name="quantite" id="quantite" value="1" min="1"
                    class="w-full border-gray-300 rounded-lg p-3 shadow-sm">
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-2">Personnalisation</label>
                <div class="flex items-center gap-6">
                    <label class="inline-flex items-center">
                        <input type="radio" name="custom" value="1" x-model="custom" checked
                            class="text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-gray-700">Oui</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="custom" value="0" x-model="custom"
                            class="text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-gray-700">Non</span>
                    </label>
                </div>
            </div>

            @if ($mesures->isNotEmpty())
                <div x-bind:class="custom == '1' ? '' : 'opacity-50 pointer-events-none'">
                    <h3 class="font-semibold text-gray-700 mt-6 mb-2">Mesures personnalisées</h3>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach ($mesures as $mesure)
                            <div>
                                <label class="block text-gray-600">{{ $mesure->label }}</label>
                                <input type="number" step="0.1" name="mesures[{{ $mesure->id }}]"
                                    value="{{ old('mesures.' . $mesure->id, $mesure->valeur_par_defaut) }}"
                                    class="w-full border-gray-300 rounded-lg p-2">
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-500 italic">Ce modèle n’a pas de mesures personnalisables définies.</p>
            @endif


            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                    Créer la commande
                </button>
            </div>
        </form>
    </div>
@endsection
