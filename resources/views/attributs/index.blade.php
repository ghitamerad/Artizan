
@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto p-6 space-y-6">

    {{-- Formulaire de création d'attribut --}}
    <div class="bg-white p-6 rounded-2xl shadow-md">
        <h2 class="text-xl font-bold mb-4">Créer un attribut</h2>
        <form method="POST" action="{{ route('attributs.store') }}" class="space-y-4">
            @csrf
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-2 sm:space-y-0">
                <input type="text" name="nom" placeholder="Nom de l'attribut"
                    class="flex-1 border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                <label class="inline-flex items-center space-x-2">
                    <input type="checkbox" name="obligatoire"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring focus:ring-blue-200">
                    <span>Obligatoire</span>
                </label>
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition">Ajouter</button>
            </div>
        </form>
    </div>

    {{-- Liste des attributs --}}
    <div class="space-y-4">
        @foreach($attributs as $attribut)
        <div class="bg-white p-4 rounded-xl shadow border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="text-lg font-semibold flex items-center gap-2">
                    {{ $attribut->nom }}
                    <span class="text-sm text-gray-600">
                        {{ $attribut->obligatoire ? '✅ Obligatoire' : '❌ Optionnel' }}
                    </span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('valeurs.create', ['attribut' => $attribut->id]) }}" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm">➕ Valeur</a>

                    <a href="{{ route('attributs.edit', $attribut) }}"
                        class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-lg text-sm">✏️ Modifier</a>

                    <form method="POST" action="{{ route('attributs.destroy', $attribut) }}"
                        onsubmit="return confirm('Supprimer cet attribut ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm">🗑️ Supprimer</button>
                    </form>
                </div>
            </div>

            {{-- Liste des valeurs de l'attribut --}}
            @if($attribut->valeurs->count())
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                @foreach($attribut->valeurs as $valeur)
                <div class="bg-gray-100 p-2 rounded-md text-gray-800">
                    {{ $valeur->nom }}
                </div>
                @endforeach
            </div>
            @else
            <div class="text-gray-500 text-sm mt-2">Aucune valeur pour cet attribut.</div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection
