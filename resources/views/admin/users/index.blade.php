@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-2xl">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Liste des Utilisateurs</h2>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            <i data-lucide="plus" class="w-5 h-5"></i> Ajouter
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded-lg">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="p-4 text-left">ID</th>
                    <th class="p-4 text-left">Nom</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-left">Rôle</th>
                    <th class="p-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse($users as $user)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-4">{{ $user->id }}</td>
                    <td class="p-4 font-medium">{{ $user->name }}</td>
                    <td class="p-4">{{ $user->email }}</td>
                    <td class="p-4 capitalize">
                       @php
                            $roleColors = [
                                'admin' => 'bg-purple-100 text-purple-800',
                                'gerante' => 'bg-indigo-100 text-indigo-800',
                                'couturiere' => 'bg-yellow-100 text-yellow-800',
                                'client' => 'bg-blue-200 text-blue-800',
                            ];
                            $color = $roleColors[$user->role] ?? 'bg-blue-100 text-gray-700';
                            $roleLabel = $user->role === 'gerante' ? 'responsable' : $user->role;

                        @endphp
                        <span class="inline-flex items-center gap-1 px-2 py-1 text-sm rounded {{ $color }}">
                            <i data-lucide="user" class="w-4 h-4"></i> {{ $roleLabel }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center gap-1 px-3 py-1 text-sm bg-yellow-500 text-white rounded hover:bg-yellow-600 transition" title="Modifier">
                                <i data-lucide="edit-2" class="w-4 h-4"></i> Modifier
                            </a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700 transition" title="Supprimer">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Aucun utilisateur trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
