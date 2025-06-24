@extends('layouts.test2')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-10 space-y-8">

        {{-- 🔓 Déconnexion --}}
        <div class="flex justify-end">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-1 px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg text-sm hover:bg-red-100 transition">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    Déconnexion
                </button>
            </form>
        </div>

        {{-- 🔹 Profil utilisateur --}}
        <div
            class="bg-white rounded-2xl shadow-md p-6 flex flex-col sm:flex-row items-center sm:items-start justify-between gap-6">
            <div class="flex items-center gap-4">
                {{-- Icône utilisateur statique --}}
                <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center border-2 border-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-[#2C3E50]">{{ Auth::user()->name }}</h2>
                    <p class="text-gray-500 text-sm">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <div>
                <a href="{{ route('profile') }}"
                    class="inline-flex items-center gap-1 px-4 py-2 bg-[#2C3E50] text-white text-sm font-medium rounded-lg hover:bg-[#1A2530] transition">
                    Modifier le profil
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- 🔔 Notifications --}}
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-lg font-semibold text-[#2C3E50]">
                    <i data-lucide="bell" class="w-5 h-5 text-[#2C3E50]"></i>
                    <h2>Mes notifications</h2>
                </div>
                <a href="{{ route('notifications.client') }}"
                    class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                    Voir toutes
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>

        {{-- 🛒 Commandes --}}
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-lg font-semibold text-[#2C3E50]">
                    <i data-lucide="shopping-cart" class="w-5 h-5 text-[#2C3E50]"></i>
                    <h2>Mes commandes</h2>
                </div>
                <a href="{{ route('detail-commandes.index') }}"
                    class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                    Voir toutes
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>

        {{-- 📄 Devis --}}
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-lg font-semibold text-[#2C3E50]">
                    <i data-lucide="file-text" class="w-5 h-5 text-[#2C3E50]"></i>
                    <h2>Mes devis</h2>
                </div>
                <a href="{{ route('mes-devis.index') }}"
                    class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                    Voir tous
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>

    </div>
@endsection
