@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto p-6 space-y-6">
        {{-- Formulaire de création de type attribut --}}
        <div class="bg-white p-6 rounded-2xl shadow-md">
            <h2 class="text-xl font-bold mb-4">Créer un type attribut</h2>
            <form method="POST" action="{{ route('attributs.store') }}" class="space-y-4">
                @csrf
                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-2 sm:space-y-0">
                    <input type="text" name="nom" placeholder="Nom du type attribut"
                        class="flex-1 border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="obligatoire"
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring focus:ring-blue-200">
                        <span>Obligatoire</span>
                    </label>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-[#05335E] hover:bg-blue-800 text-white px-5 py-2 rounded-xl shadow transition">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                        <span>Ajouter</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Liste des types attributs --}}
        <div class="space-y-4">
            @foreach ($attributs as $attribut)
                <div class="bg-white p-4 rounded-xl shadow border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="text-lg font-semibold flex items-center gap-2">
                            {{ $attribut->nom }}
                            @if ($attribut->obligatoire)
                                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                                <span class="text-sm text-gray-600">Obligatoire</span>
                            @else
                                <i data-lucide="x-circle" class="w-5 h-5 text-red-500"></i>
                                <span class="text-sm text-gray-600">Optionnel</span>
                            @endif
                        </div>
                        <div class="flex gap-2 flex-wrap">
                            <a href="{{ route('valeurs.create', ['attribut' => $attribut->id]) }}"
                                class="flex items-center gap-1 px-3 py-1 border border-green-200 bg-green-50 text-green-700 rounded-md shadow-sm hover:bg-green-100 transition text-sm">
                                <i data-lucide="plus-circle" class="w-4 h-4"></i> Ajouter
                            </a>
                            <a href="{{ route('attributs.edit', $attribut) }}"
                                class="flex items-center gap-1 px-3 py-1 border border-yellow-200 bg-yellow-50 text-yellow-700 rounded-md shadow-sm hover:bg-yellow-100 transition text-sm">
                                <i data-lucide="pencil" class="w-4 h-4"></i> Modifier
                            </a>
                            <form method="POST" action="{{ route('attributs.destroy', $attribut) }}"
                                onsubmit="return confirm('Supprimer ce type attribut ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex items-center gap-1 px-3 py-1 border border-red-200 bg-red-50 text-red-700 rounded-md shadow-sm hover:bg-red-100 transition text-sm">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Liste des attributs --}}
                    @if ($attribut->valeurs->count())
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach ($attribut->valeurs as $valeur)
                                <div class="bg-gray-100 p-2 rounded-md text-gray-800 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        @if ($valeur->image)
                                            <img src="{{ asset('storage/' . $valeur->image) }}"
                                                alt="Image de {{ $valeur->nom }}"
                                                class="w-10 h-10 object-cover rounded border border-gray-300">
                                        @else
                                            <div
                                                class="w-10 h-10 bg-gray-200 rounded border border-gray-300 flex items-center justify-center text-xs text-gray-500">
                                                N/A
                                            </div>
                                        @endif
                                        <span>{{ $valeur->nom }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('valeurs.edit', $valeur) }}"
                                            class="text-blue-600 hover:text-blue-800" title="Modifier">
                                            <i data-lucide="pencil" class="w-5 h-5"></i>
                                        </a>
                                        <form action="{{ route('valeurs.destroy', $valeur) }}" method="POST"
                                            onsubmit="return confirm('Supprimer cet attribut ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800"
                                                title="Supprimer">
                                                <i data-lucide="trash" class="w-5 h-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-gray-500 text-sm mt-2">Aucun attribut défini pour ce type.</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
@endsection
