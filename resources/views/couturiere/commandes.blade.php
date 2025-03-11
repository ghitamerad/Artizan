@extends('layouts.couturiere')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">📦 Commandes assignées</h2>

    @if(session('success'))
        <div class="bg-green-500 text-white p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($commandes->isEmpty())
        <p class="text-gray-600">Aucune commande en cours.</p>
    @else
        <div class="bg-white p-4 shadow rounded-lg">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 p-2">Modèle</th>
                        <th class="border border-gray-300 p-2">Client</th>
                        <th class="border border-gray-300 p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($commandes as $commande)
                        <tr class="border border-gray-300">
                            <td class="p-2">{{ $commande->modele->nom ?? 'N/A' }}</td>
                            <td class="p-2">{{ $commande->commande->client->name ?? 'N/A' }}</td>
                            <td class="p-2">
                                <form action="{{ route('couturiere.commandes.terminer', $commande->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">
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
