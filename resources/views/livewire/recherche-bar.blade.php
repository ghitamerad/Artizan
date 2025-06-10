<div class=" text-black">
    <form wire:submit.prevent="redirectToSearch" class="relative flex items-center">
        <input type="text" wire:model="search" placeholder="Rechercher..."
            class="w-32 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2C3E50]" />

        <button type="submit" class="absolute right-3">
            <!-- Icône Lucide Search -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 hover:text-[#2C3E50]" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
        </button>
    </form>
</div>

