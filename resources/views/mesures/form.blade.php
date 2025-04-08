@extends('layouts.test2')

@section('content')
    <div class="container">
        <h1>Remplir les mesures pour le modèle {{ $modele->nom }}</h1>
        @livewire('mesures-form', ['modeleId' => $modele->id])
    </div>
@endsection

