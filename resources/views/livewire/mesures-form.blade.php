<div class="container mx-auto max-w-3xl p-8 bg-white shadow-xl rounded-lg mt-10">
    <h2 class="text-3xl font-bold text-[#2C3E50] mb-6 text-center">
        Sélectionnez vos mesures pour
        <span class="text-[#D4AF37]">{{ $modele->nom }}</span>
    </h2>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-800 border border-green-300 rounded-lg shadow">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="bg-[#F5F5DC] p-6 rounded-xl shadow-lg">
            @foreach($mesures as $mesure)
                <div class="mb-6">
                    <label for="mesure_{{ $mesure->id }}" class="block text-[#2C3E50] font-semibold mb-2">
                        {{ $mesure->label }}
                    </label>
                    <input type="number" step="0.01" wire:model.defer="values.{{ $mesure->label }}" id="mesure_{{ $mesure->id }}"
                        class="text-[#2C3E50] w-full border-gray-300 rounded-lg shadow-sm focus:border-[#D4AF37] focus:ring focus:ring-[#D4AF37] p-3 text-lg">
                    @error('values.' . $mesure->label)
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            @endforeach
        </div>

        <button type="submit" class="w-full bg-[#D4AF37] text-white font-semibold py-3 px-6 rounded-lg hover:bg-[#C19B2C] transition-all shadow-md">
            Enregistrer mes mesures
        </button>
    </form>
</div>
