@extends('layouts.test2')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h2 class="text-2xl font-bold mb-6">Mes devis</h2>

    @if ($devis->isEmpty())
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg shadow">
            Vous n'avez pas encore de devis.
        </div>
    @else
        <div class="bg-white shadow-md rounded-xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">#</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Statut</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tarif</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($devis as $devi)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">#{{ $devi->id }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $devi->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($devi->statut === 'propose')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Proposé</span>
                                @elseif($devi->statut === 'aceptee')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Accepté</span>
                                @elseif($devi->statut === 'refusee')
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Refusé</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">En attente</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ $devi->tarif ? number_format($devi->tarif, 2, ',', ' ') . ' DA' : '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                <a href="{{ route('devis.client.show', $devi) }}"
                                   class="text-indigo-600 hover:underline font-medium">
                                    Voir détails
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
