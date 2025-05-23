<div class="bg-white border border-gray-200 rounded-xl p-6 shadow-md space-y-6">
    @if ($categorieFinale)
        <div>
            <h2 class="text-lg font-bold text-blue-700">Votre sélection finale</h2>
            <p class="text-sm text-gray-600">Catégorie choisie : <strong>{{ $categorieFinale->nom }}</strong></p>
        </div>

        <div class="space-y-4">
            @foreach ($attributs as $attributId => $data)
                @php
                    $valeurId = $selectedValeurs[$attributId] ?? null;
                    $valeurNom = $valeurId ? ($data['valeurs'][$valeurId] ?? 'Non défini') : 'Non sélectionné';
                    $valeurImage = $data['images'][$valeurId] ?? null;
                @endphp
                <div class="flex items-center gap-4">
                    @if ($valeurImage)
                        <img src="{{ asset('storage/' . $valeurImage) }}" class="w-14 h-14 object-cover rounded-lg border">
                    @endif
                    <div>
                        <p class="font-medium text-gray-800">{{ $data['nom'] }}</p>
                        <p class="text-sm text-gray-500">{{ $valeurNom }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 italic">Aucune sélection effectuée pour l’instant.</p>
    @endif
</div>

