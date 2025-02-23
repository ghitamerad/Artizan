@extends('layouts.app') {{-- Utilise le bon layout --}}

@section('content')
<div class="container">
    <h5 class="mt-4">Modèles commandés :</h5>
    @if ($commande->details && $commande->details->count() > 0)
        <ul>
            @foreach($commande->details as $detail)
                <li>{{ $detail->modele->nom }} - {{ $detail->prix_unitaire }} € (x{{ $detail->quantite }})</li>
            @endforeach
        </ul>
    @else
        <p>Aucun modèle associé à cette commande.</p>
    @endif
</div>
@endsection
