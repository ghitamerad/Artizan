@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">📦 Mes Commandes</h2>

    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded-lg mb-6 text-center shadow-md">
            {{ session('success') }}
        </div>
    @endif

    @if($commandes->isEmpty())
        <p class="text-gray-600 text-lg text-center">Aucune commande en cours.</p>
    @else
        <div class="bg-white p-6 shadow-lg rounded-lg">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-300 text-gray-800">
                        <th class="p-4 text-left">ID Détail</th>
                        <th class="p-4 text-left">Modèle</th>
                        <th class="p-4 text-left">Client</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($commandes as $commande)
                        <tr class="border-b border-gray-300 hover:bg-gray-100 transition">
                            <td class="p-4 text-gray-800 font-semibold">#{{ $commande->id }}</td>
                            <td class="p-4 text-gray-800">{{ $commande->modele->nom ?? 'N/A' }}</td>
                            <td class="p-4 text-gray-800 font-semibold">{{ $commande->commande->user->name ?? 'N/A' }}</td>
                            <td class="p-4 flex justify-center space-x-3">
                                <!-- Bouton Voir -->
                                <a href="{{ route('commandes.details', $commande->id) }}"
                                   class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 transition">
                                    👁️ Voir
                                </a>

                                <!-- Bouton Terminer -->
                                <form action="{{ route('couturiere.commandes.terminer', $commande->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="px-4 py-2 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 transition">
                                        ✅ Terminer
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
