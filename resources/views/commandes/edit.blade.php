@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-lg mt-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">✏️ Modifier la commande #{{ $commande->id }}</h2>

    <form action="{{ route('commandes.update', $commande) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-4">
            <label for="statut" class="block text-gray-700 font-medium mb-2">📌 Statut</label>
            <select class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400"
                    name="statut">
                <option value="en_attente" {{ $commande->statut == 'en_attente' ? 'selected' : '' }}>🕒 En attente</option>
                <option value="validee" {{ $commande->statut == 'validee' ? 'selected' : '' }}>✅ Validée</option>
                <option value="refusee" {{ $commande->statut == 'refusee' ? 'selected' : '' }}>❌ Refusée</option>
            </select>
        </div>

        <button type="submit"
                class="w-full px-4 py-3 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition">
            💾 Enregistrer
        </button>
    </form>
</div>
@endsection
