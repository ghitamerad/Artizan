@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto py-10 px-6">
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-8 shadow-sm">

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Liste des Catégories</h1>
                <a href="{{ route('categories.create') }}"
                   class="inline-flex items-center gap-2 bg-[#05335E] hover:bg-blue-800 text-white px-5 py-2 rounded-xl shadow transition">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>Créer une catégorie</span>
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 text-green-800 border border-green-300 rounded-lg px-4 py-3 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($categories->isEmpty())
                <p class="text-gray-600">Aucune catégorie trouvée.</p>
            @else
                <div class="bg-white rounded-2xl shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-200 text-gray-700 text-base font-semibold uppercase">
                            <tr>
                                <th class="px-6 py-4 text-left">Nom</th>
                                <th class="px-6 py-4 text-left">Catégorie parente</th>
                                <th class="px-6 py-4 text-left">Image</th>
                                <th class="px-6 py-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-800 text-base">
                            @foreach($categories as $categorie)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">{{ $categorie->nom }}</td>
                                    <td class="px-6 py-4">
                                        {{ $categorie->parent ? $categorie->parent->nom : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($categorie->image)
                                            <img src="{{ asset('storage/' . $categorie->image) }}"
                                                 alt="{{ $categorie->nom }}"
                                                 class="w-16 h-16 object-cover rounded">
                                        @else
                                            <div class="w-16 h-16 flex items-center justify-center bg-gray-200 rounded text-gray-500 text-sm">
                                                N/A
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-left space-x-2 flex justify-center gap-2 flex-wrap">
                                        <a href="{{ route('categories.edit', $categorie) }}"
                                           class="flex items-center gap-1 px-3 py-1 border border-yellow-200 bg-yellow-50 text-yellow-700 rounded-md shadow-sm hover:bg-yellow-100 transition">
                                            <i data-lucide="pencil" class="w-4 h-4"></i> Modifier
                                        </a>

                                        <form action="{{ route('categories.destroy', $categorie) }}" method="POST"
                                              onsubmit="return confirm('Confirmer la suppression ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="flex items-center gap-1 px-3 py-1 border border-red-200 bg-red-50 text-red-700 rounded-md shadow-sm hover:bg-red-100 transition">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i> Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
@endsection
