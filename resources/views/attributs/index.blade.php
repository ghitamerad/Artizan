
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
                <div class="bg-gray-100 p-2 rounded-md text-gray-800 flex items-center justify-between">
                    {{-- Nom de la valeur --}}
                    <span>{{ $valeur->nom }}</span>
            
                    {{-- Icônes d'actions --}}
                    <div class="flex items-center gap-2">
                        {{-- Modifier --}}
                        <a href="{{ route('valeurs.edit', $valeur) }}" class="text-blue-600 hover:text-blue-800" title="Modifier">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                              </svg>                              
                        </a>
            
                        {{-- Supprimer --}}
                        <form action="{{ route('valeurs.destroy', $valeur) }}" method="POST"
                            onsubmit="return confirm('Supprimer cette valeur ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                  </svg>
                                  
                            </button>
                        </form>
                    </div>
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
