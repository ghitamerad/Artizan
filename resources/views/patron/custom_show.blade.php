@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>apercu du patron personnalisé</h2>

        {{-- Vérifier si le fichier existe --}}
        @if($customPatronPath)
            <object type="image/svg+xml" data="{{ $customPatronPath }}" class="w-100" style="height: 600px;">
                Votre navigateur ne prend pas en charge l'affichage des fichiers SVG.
            </object>
        @else
            <p class="text-danger">Aucun patron personnalisé disponible.</p>
        @endif
    </div>
@endsection
