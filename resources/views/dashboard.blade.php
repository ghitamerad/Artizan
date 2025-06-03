@extends('layouts.test2')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8 space-y-8">

    {{-- 🔹 Profil --}}
    <div class="bg-white rounded-2xl shadow p-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold">Mon profil</h2>
        <a href="{{ route('profile') }}" class="text-blue-600 hover:underline">Modifier <span class="ml-1">→</span></a>
    </div>

    {{-- 🔹 Commandes --}}
    <div x-data="{ open: false }" class="bg-white rounded-2xl shadow p-6">
        <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
            <h2 class="text-xl font-semibold">Mes commandes</h2>
            <svg :class="{ 'rotate-180': open }" class="w-5 h-5 transform transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        <div x-show="open" x-transition class="mt-4 ml-4 space-y-2 text-sm">
            <a href="{{ route('detail-commandes.index', ['filtre' => 'toutes']) }}"
               class="{{ request()->get('filtre') === 'toutes' ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-500' }}">
               Toutes mes commandes
            </a>
            <a href="{{ route('detail-commandes.index', ['filtre' => 'en_cours']) }}"
               class="{{ request()->get('filtre') === 'en_cours' ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-500' }}">
               Commandes en cours
            </a>
            <a href="{{ route('detail-commandes.index', ['filtre' => 'terminees']) }}"
               class="{{ request()->get('filtre') === 'terminees' ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-500' }}">
               Commandes terminées
            </a>
        </div>
    </div>

    {{-- 🔹 Devis --}}
    <div x-data="{ open: false }" class="bg-white rounded-2xl shadow p-6">
        <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
            <h2 class="text-xl font-semibold">Mes devis</h2>
            <svg :class="{ 'rotate-180': open }" class="w-5 h-5 transform transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        <div x-show="open" x-transition class="mt-4 ml-4 space-y-2 text-sm">
            <a href="{{ route('mes-devis.index', ['filtre' => 'attente']) }}"
               class="{{ request()->get('filtre') === 'attente' ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-500' }}">
               En attente
            </a>
            <a href="{{ route('mes-devis.index', ['filtre' => 'acceptes']) }}"
               class="{{ request()->get('filtre') === 'acceptes' ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-500' }}">
               Acceptés
            </a>
            <a href="{{ route('mes-devis.index', ['filtre' => 'refuses']) }}"
               class="{{ request()->get('filtre') === 'refuses' ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-500' }}">
               Refusés
            </a>
        </div>
    </div>

</div>
@endsection
