<div class="relative flex items-center text-black">
    {{-- Icône loupe (si champ fermé) --}}
    @if (!$showInput && empty($search))
        <button wire:click="toggleInput" class="flex items-center text-gray-700 hover:text-[#05335E] transition">
            <i data-lucide="search" class="w-5 h-5"></i>
        </button>
    @endif

    {{-- Champ de recherche --}}
    @if ($showInput || !empty($search))
        <form wire:submit.prevent="redirectToSearch" class="relative flex items-center">
            <input type="text"
                   wire:model="search"
                   wire:keydown.escape="resetSearch"
                   placeholder="Rechercher..."
                   class="h-9 w-40 sm:w-48 md:w-56 px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2C3E50] transition-all duration-300 text-sm" />

            {{-- Icône loupe dans input --}}
            @if (empty($search))
                <button type="submit" class="absolute right-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 hover:text-[#2C3E50]"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2" fill="none" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor" stroke-width="2" />
                    </svg>
                </button>
            @endif

            {{-- Bouton réinitialiser --}}
            @if (!empty($search))
                <button type="button" wire:click="resetSearch"
                    class="absolute right-2 text-red-500 text-xl leading-none">
                    &times;
                </button>
            @endif
        </form>
    @endif
</div>
