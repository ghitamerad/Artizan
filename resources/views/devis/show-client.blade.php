@extends('layouts.test2')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 text-black">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Détails du devis</h2>

    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <p class="mb-2"><span class="font-semibold">Catégorie :</span> {{ $devi->categorie->nom }}</p>
        <p class="mb-2"><span class="font-semibold">Description :</span> {{ $devi->description ?? 'Non spécifiée' }}</p>


        <p class="mb-2"><span class="font-semibold">Tarif proposé :</span>
            @if ($devi->tarif)
                <span class="text-green-600 font-bold">{{ $devi->tarif }} DA</span>
            @else
                <span class="text-gray-500 italic">Pas encore proposé</span>
            @endif
        </p>

        <div class="mb-4">
            <p class="font-semibold mb-1">Attributs sélectionnés :</p>
            <ul class="list-disc list-inside text-gray-700">
                @foreach ($devi->attributValeurs as $valeur)
                    <li>{{ $valeur->attribut->nom }} : {{ $valeur->nom }}</li>
                @endforeach
            </ul>
        </div>

        <p><span class="font-semibold">Statut :</span>
            @if ($devi->statut === 'aceptee')
                <span class="ml-2 inline-block px-3 py-1 bg-green-100 text-green-700 text-sm rounded-full">Accepté</span>
            @elseif ($devi->statut === 'refusee')
                <span class="ml-2 inline-block px-3 py-1 bg-red-100 text-red-700 text-sm rounded-full">Refusé</span>
            @else
                <span class="ml-2 inline-block px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full">En attente</span>
            @endif
        </p>

        @if ($devi->image)
            <div class="mb-4">
                <span class="font-semibold">Image :</span><br>
                <img src="{{ asset('storage/' . $devi->image) }}" alt="Image du devis" class="mt-2 rounded-lg w-40 max-w-sm">
            </div>
        @endif
    </div>

    @if($devi->tarif && $devi->statut=== "en_attente")
        <div class="flex space-x-4">
            <form action="{{ route('devis.repondreClient', $devi) }}" method="POST">
                @csrf
                <input type="hidden" name="statut" value="aceptee">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
                    Accepter le devis
                </button>
            </form>

            <form action="{{ route('devis.repondreClient', $devi) }}" method="POST">
                @csrf
                <input type="hidden" name="statut" value="refusee">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow">
                    Refuser le devis
                </button>
            </form>
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('mes-devis.index') }}" class="text-indigo-600 hover:underline">&larr; Retour à mes devis</a>
    </div>
</div>
@endsection
