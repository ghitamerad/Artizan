@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto mt-10 bg-white p-6 rounded-xl shadow-lg">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Catégories</h1>
        <a href="{{ route('categories.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            + Créer une catégorie
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($categories->isEmpty())
        <p class="text-gray-600">Aucune catégorie trouvée.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 border-b">Nom</th>
                        <th class="px-6 py-3 border-b">Catégorie parente</th>
                        <th class="px-6 py-3 border-b text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $categorie)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $categorie->nom }}</td>
                            <td class="px-6 py-4">
                                {{ $categorie->parent ? $categorie->parent->nom : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('categories.edit', $categorie) }}"
                                   class="inline-block px-4 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition">
                                    Modifier
                                </a>

                                <form action="{{ route('categories.destroy', $categorie) }}" method="POST" class="inline-block">
                              @csrf
                              @method('DELETE')
                              <button type="submit"
                                      class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                                  Supprimer
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
@endsection
