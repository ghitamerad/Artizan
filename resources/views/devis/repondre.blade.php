@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto mt-8 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Répondre au devis</h2>

    <form method="POST" action="{{ route('devis.repondre', $devi) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="tarif" class="block font-semibold text-gray-700">Tarif proposé (DA)</label>
            <input type="number" step="0.01" name="tarif" id="tarif" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
        </div>

        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">Envoyer la réponse</button>
    </form>
</div>
@endsection
