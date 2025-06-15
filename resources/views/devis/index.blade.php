@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-6">
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-8 shadow-sm">

            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Liste des Devis</h1>

                <a href="{{ route('devis.create') }}"
                    class="inline-flex items-center gap-2 bg-[#05335E] hover:bg-blue-800 text-white px-5 py-2 rounded-xl shadow transition">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>Nouveau devis</span>
                </a>
            </div>

            @if (session('message'))
                <div class="bg-green-100 text-green-800 border border-green-300 rounded-lg px-4 py-3 mb-6">
                    {{ session('message') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-base text-gray-800">
                    <thead class="bg-gray-200 text-gray-700 text-base font-semibold">
                        <tr>
                            <th class="px-6 py-4 text-left">#</th>
                            <th class="px-6 py-4 text-left">Client</th>
                            <th class="px-6 py-4 text-left">Description</th>
                            <th class="px-6 py-4 text-left">Catégorie</th>
                            <th class="px-6 py-4 text-left">Image</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($devis as $devi)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">{{ $devi->id }}</td>
                                <td class="px-6 py-4">{{ $devi->utilisateur->name }}</td>
                                <td class="px-6 py-4">{{ $devi->description }}</td>
                                <td class="px-6 py-4">{{ $devi->categorie->nom ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @if ($devi->image)
                                        <img src="{{ asset('storage/' . $devi->image) }}" alt="Image" class="w-16 h-16 object-cover rounded">
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-3 flex-wrap justify-end">
                                        <a href="{{ route('devis.show', $devi) }}"
                                            class="flex items-center gap-1 px-3 py-1 border border-blue-200 bg-blue-50 text-blue-700 rounded-md shadow-sm hover:bg-blue-100 transition">
                                            <i data-lucide="eye" class="w-4 h-4"></i> Voir
                                        </a>
                                        <a href="{{ route('devis.edit', $devi) }}"
                                            class="flex items-center gap-1 px-3 py-1 border border-yellow-200 bg-yellow-50 text-yellow-700 rounded-md shadow-sm hover:bg-yellow-100 transition">
                                            <i data-lucide="pencil" class="w-4 h-4"></i> Modifier
                                        </a>
                                        <form action="{{ route('devis.destroy', $devi) }}" method="POST"
                                            onsubmit="return confirm('Supprimer ce devis ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="flex items-center gap-1 px-3 py-1 border border-red-200 bg-red-50 text-red-700 rounded-md shadow-sm hover:bg-red-100 transition">
                                                <i data-lucide="trash" class="w-4 h-4"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Aucun devis trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
