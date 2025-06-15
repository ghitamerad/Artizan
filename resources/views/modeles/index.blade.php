@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto py-10 px-6">
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-8 shadow-sm">

            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Liste des Modèles</h1>

                @can('create', App\Models\Modele::class)
                    <a href="{{ route('modeles.create') }}"
                        class="inline-flex items-center gap-2 bg-[#05335E] hover:bg-blue-800 text-white px-5 py-2 rounded-xl shadow transition">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                        <span>Ajouter</span>
                    </a>
                @endcan
            </div>

            @if (session()->has('message'))
                <div class="bg-green-100 text-green-800 border border-green-300 rounded-lg px-4 py-3 mb-6">
                    {{ session('message') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-200 text-gray-700 text-5xs font-semibold">
                        <tr>
                            <th class="px-6 py-4 text-left">#</th>
                            <th class="px-6 py-4 text-left">Nom</th>
                            <th class="px-6 py-4 text-left">Prix (DZD)</th>
                            <th class="px-6 py-4 text-left">Sur-mesure</th>
                            <th class="px-6 py-4 text-left">Image</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-3xs text-gray-800">
                        @foreach ($modeles as $modele)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">{{ $modele->id }}</td>
                                <td class="px-6 py-4">{{ $modele->nom }}</td>
                                <td class="px-6 py-4">{{ number_format($modele->prix, 0, ',', ' ') }} DZD</td>
                                <td class="px-6 py-4">
                                    @if ($modele->sur_commande)
                                        <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                                    @else
                                        <i data-lucide="x-circle" class="w-5 h-5 text-red-500"></i>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($modele->image)
                                        <img src="{{ asset('storage/' . $modele->image) }}" alt="{{ $modele->nom }}"
                                            class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div
                                            class="w-16 h-16 flex items-center justify-center bg-gray-200 rounded text-gray-500 text-sm">
                                            N/A</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-4 flex-wrap items-center">
                                        <a href="{{ route('modeles.show', $modele) }}"
                                            class="flex items-center gap-1 px-3 py-1 border border-blue-200 bg-blue-50 text-blue-700 rounded-md shadow-sm hover:bg-blue-100 transition">
                                            <i data-lucide="eye" class="w-4 h-4"></i> Voir
                                        </a>

                                        @can('update', $modele)
                                            <a href="{{ route('modeles.edit', $modele) }}"
                                                class="flex items-center gap-1 px-3 py-1 border border-yellow-200 bg-yellow-50 text-yellow-700 rounded-md shadow-sm hover:bg-yellow-100 transition">
                                                <i data-lucide="pencil" class="w-4 h-4"></i> Modifier
                                            </a>
                                        @endcan

                                        @can('delete', $modele)
                                            <form action="{{ route('modeles.destroy', $modele) }}" method="POST"
                                                onsubmit="return confirm('Confirmer la suppression ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex items-center gap-1 px-3 py-1 border border-red-200 bg-red-50 text-red-700 rounded-md shadow-sm hover:bg-red-100 transition">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i> Supprimer
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
@endsection
