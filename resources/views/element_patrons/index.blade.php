@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Éléments de Patron</h1>
        <a href="{{ route('element-patrons.create') }}" class="inline-flex items-center gap-2 bg-[#05335E] hover:bg-blue-800 text-white px-5 py-2 rounded-xl shadow transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            <span>Ajouter</span>
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 border border-green-300 rounded-lg px-4 py-3 mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-200 text-gray-700 text-5xs font-semibold">
                <tr>
                    <th class="px-6 py-4 text-left">Catégorie</th>
                    <th class="px-6 py-4 text-left">Valeur d'attribut</th>
                    <th class="px-6 py-4 text-left">Fichier Patron</th>
                    <th class="px-6 py-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-3xs text-gray-800">
                @forelse($elements as $e)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">{{ $e->categorie->nom }}</td>
                        <td class="px-6 py-4">{{ $e->attributValeur->nom }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('element-patrons.show', $e) }}" class="text-blue-600 hover:underline">
                                Voir le fichier
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-4 items-center">
                                <a href="{{ route('element-patrons.edit', $e) }}" class="text-yellow-600 hover:text-yellow-700 flex items-center gap-1">
                                    <i data-lucide="pencil" class="w-4 h-4"></i> Modifier
                                </a>
                                <form action="{{ route('element-patrons.destroy', $e) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 flex items-center gap-1">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-gray-500">Aucun élément trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
