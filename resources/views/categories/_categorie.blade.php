<li>
    <div class="text-gray-700">{{ $categorie->nom }}</div>

    @if($categorie->enfants->isNotEmpty())
        <ul class="ml-6 border-l border-gray-300 pl-4 mt-2 space-y-2">
            @foreach($categorie->enfants as $enfant)
                @include('categories._categorie', ['categorie' => $enfant])
            @endforeach
        </ul>
    @endif
</li>
