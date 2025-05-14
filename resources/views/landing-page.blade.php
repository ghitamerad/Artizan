<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lebsa Zina - Élégance Traditionnelle Algérienne</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #F7F3E6; /* Votre beige de fond */
            font-family: 'Georgia', serif; /* Votre police préférée */
        }
        /* Vos couleurs personnalisées */
        .bg-brand-beige { background-color: #F7F3E6; }
        .text-brand-blue { color: #05335E; }
        .bg-brand-blue { background-color: #05335E; }
        .text-brand-gold { color: #C19B2C; }
        .bg-brand-gold { background-color: #C19B2C; }
        .border-brand-gold { border-color: #C19B2C; }

        /* Couleurs de survol personnalisées */
        .hover\:bg-brand-gold-dark:hover { background-color: #a38123; } /* Doré un peu plus foncé */
        .hover\:bg-brand-blue-dark:hover { background-color: #032242; } /* Bleu un peu plus foncé */
        .hover\:text-brand-blue:hover { color: #05335E; }


        /* Amélioration de la lisibilité du texte sur l'image de fond de la section Hero */
        .hero-section-overlay::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.25) 0%, rgba(0,0,0,0) 60%);
            z-index: 1;
        }
         .hero-text-shadow {
            text-shadow: 0px 1px 3px rgba(0, 0, 0, 0.4); /* Ombre pour le texte */
        }
    </style>
</head>
<body class="text-gray-800">

    <!-- HERO / INTRO (Votre structure originale avec améliorations) -->
    <section class="relative bg-brand-beige min-h-screen flex items-center justify-center pt-16 pb-10 sm:pt-10 sm:pb-0">
        {{-- <div class="absolute inset-0 z-0 hero-section-overlay">
            <img src="{{ asset('images/optional-hero-background.jpg') }}"
                 alt="Arrière-plan Lebsa Zina"
                 class="w-full h-full object-cover object-center opacity-70">
        </div> --}}

        <div class="relative z-10 max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 items-center gap-10 md:gap-16">
            <div class="space-y-6 text-center md:text-left">
                <h1 class="text-5xl lg:text-6xl font-extrabold text-brand-blue hero-text-shadow">Lebsa Zina</h1>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 hero-text-shadow">
                    L'Élégance <span class="text-brand-gold">Traditionnelle</span>,
                    <span class="block sm:inline">Votre Style <span class="text-brand-gold">Unique</span>.</span>
                </h2>
                <p class="text-lg text-slate-700 max-w-md mx-auto md:mx-0 hero-text-shadow leading-relaxed">
                    Découvrez notre collection de tenues algériennes authentiques.
                    Achat, confection sur mesure et bientôt location pour sublimer tous vos événements.
                </p>
                <a href="{{ route('home') }}"
                   class="inline-block bg-brand-blue hover:bg-brand-gold text-white hover:text-brand-blue py-3.5 px-10 sm:py-4 sm:px-12 rounded-lg shadow-lg text-lg font-semibold transition-all duration-300 transform hover:scale-105 border-2 border-transparent hover:border-brand-blue">
                    Explorer les Collections
                </a>
            </div>
            <div class="flex justify-center md:justify-end">
                <img src="{{ asset('images/dame.png') }}" alt="Dame élégante en tenue traditionnelle Lebsa Zina"
                     class="w-[350px] sm:w-[380px] md:w-[450px] lg:w-[500px] object-contain rounded-lg shadow-xl transform transition-transform duration-500 hover:scale-105">
            </div>
        </div>
    </section>

    <!-- Section 2: Questionnaire "Que recherchez-vous ?" (RÉINTÉGRÉE) -->
    <section class="py-16 md:py-24 bg-brand-beige text-center px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="font-lora text-3xl md:text-4xl font-bold text-brand-blue mb-4">Votre Expérience Idéale Commence Ici</h2>
            <p class="text-slate-600 mb-12 md:mb-16 text-lg max-w-2xl mx-auto">Dites-nous ce qui vous ferait plaisir aujourd'hui.</p>
            <div class="flex flex-col md:flex-row justify-center items-stretch gap-8 md:gap-12">
                <a href="{{ route('home') }}" {{-- Lien vers la page catalogue pour l'achat --}}
                   class="group flex flex-col items-center justify-center bg-white rounded-xl shadow-lg hover:shadow-2xl cursor-pointer transition-all duration-300 p-8 transform hover:-translate-y-2 border-2 border-transparent hover:border-brand-gold flex-1">
                    <img src="{{ asset('icons/dress.svg') }}" {{-- Assurez-vous que le chemin est correct --}}
                         class="w-16 h-16 md:w-20 md:h-20 mb-6 text-brand-gold transition-transform duration-300 group-hover:scale-110" alt="Acheter une Tenue">
                    <span class="font-lora text-xl md:text-2xl font-semibold text-brand-blue group-hover:text-brand-gold transition-colors duration-300 mb-2">Acheter une Tenue</span>
                    <span class="text-sm text-slate-600 text-center">Parcourir notre prêt-à-porter</span>
                </a>
                <a href="{{ route('sur-mesure') }}" {{-- Lien vers la page de création sur mesure --}}
                   class="group flex flex-col items-center justify-center bg-white rounded-xl shadow-lg hover:shadow-2xl cursor-pointer transition-all duration-300 p-8 transform hover:-translate-y-2 border-2 border-transparent hover:border-brand-gold flex-1">
                    <img src="{{ asset('icons/needle.svg') }}" {{-- Assurez-vous que le chemin est correct --}}
                         class="w-16 h-16 md:w-20 md:h-20 mb-6 text-brand-gold transition-transform duration-300 group-hover:scale-110" alt="Confection Sur Mesure">
                    <span class="font-lora text-xl md:text-2xl font-semibold text-brand-blue group-hover:text-brand-gold transition-colors duration-300 mb-2">Confectionner Sur Mesure</span>
                    <span class="text-sm text-slate-600 text-center">Créer votre pièce unique</span>
                </a>
            </div>
             {{-- Si vous avez un composant Livewire pour un questionnaire plus détaillé : --}}
            {{-- <div class="mt-16">
                @livewire('questionnaire-selector')
            </div> --}}
        </div>
    </section>

    <!-- Section "Nos Modèles Phares" -->
    <section class="py-16 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16">
                <h2 class="font-lora text-3xl md:text-4xl font-bold text-brand-blue mb-4">Inspirations du Moment</h2>
                <p class="text-lg text-slate-700 max-w-2xl mx-auto">Laissez-vous séduire par une sélection de nos créations les plus convoitées.</p>
            </div>

            @if(isset($modelesPhares) && $modelesPhares->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                @foreach($modelesPhares->take(4) as $modele)
                <div class="group bg-brand-beige rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1.5 overflow-hidden flex flex-col">
                    <a href="{{ route('modele.show', $modele->id) }}" class="block flex flex-col h-full">
                        <div class="aspect-[3/4] overflow-hidden">
                            @if($modele->image)
                                <img src="{{ str_starts_with($modele->image, 'images/') ? asset($modele->image) : asset('storage/' . $modele->image) }}" alt="{{ $modele->nom }}" class="w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105">
                            @else
                                <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                    <svg class="h-16 w-16 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4 md:p-5 flex flex-col flex-grow">
                            <h3 class="font-lora text-lg font-semibold text-brand-blue group-hover:text-brand-gold transition-colors duration-300 leading-tight mb-1">
                                {{ Str::limit($modele->nom, 35) }}
                            </h3>
                            <span class="text-xs font-medium text-brand-gold tracking-wider uppercase mb-2">
                                {{ $modele->categorie->nom ?? 'Traditionnel' }}
                            </span>
                            <p class="text-xl font-bold text-brand-blue mt-auto">{{ number_format($modele->prix, 2, ',', ' ') }} €</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-12 md:mt-16">
                 <a href="{{ route('home') }}"
                   class="inline-block bg-brand-blue hover:bg-brand-gold text-white hover:text-brand-blue py-3 px-8 rounded-lg shadow-md text-md font-semibold transition-all duration-300 border-2 border-transparent hover:border-brand-blue">
                    Voir Tous Nos Modèles
                </a>
            </div>
            @else
            <p class="text-center text-slate-600 py-10">Nos inspirations du moment seront bientôt dévoilées. Revenez nous voir !</p>
            @endif
        </div>
    </section>

     <!-- NOS SERVICES -->
     <section class="bg-brand-beige py-16 md:py-20" id="services">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 md:mb-14 text-brand-blue">Nos Services</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-10 text-center">
                <a href="{{ route('home') }}" class="group space-y-4 p-6 rounded-lg hover:bg-white hover:shadow-xl transition-all duration-300">
                    <img src="{{ asset('icons/dress.svg') }}" class="mx-auto h-14 text-brand-gold transition-transform duration-300 group-hover:scale-110" alt="Vente">
                    <h3 class="text-xl font-semibold text-brand-blue group-hover:text-brand-gold">Vente Prêt-à-Porter</h3>
                </a>
                <div class="group space-y-4 p-6 rounded-lg hover:bg-white hover:shadow-xl transition-all duration-300 cursor-not-allowed opacity-70" title="Service de location bientôt disponible">
                    <img src="{{ asset('icons/hanger.svg') }}" class="mx-auto h-14 text-brand-gold transition-transform duration-300 group-hover:scale-110" alt="Location">
                    <h3 class="text-xl font-semibold text-brand-blue group-hover:text-brand-gold">Location (Bientôt)</h3>
                </div>
                <a href="{{ route('sur-mesure') }}" class="group space-y-4 p-6 rounded-lg hover:shadow-xl transition-all duration-300 hover:bg-white">
                    <img src="{{ asset('icons/needle.svg') }}" class="mx-auto h-14 text-brand-gold transition-transform duration-300 group-hover:scale-110" alt="Sur-mesure">
                    <h3 class="text-xl font-semibold text-brand-blue group-hover:text-brand-gold">Confection Sur-Mesure</h3>
                </a>
            </div>
        </div>
    </section>


    <!-- FOOTER -->
    <footer class="bg-brand-blue text-white py-12">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-10">
            <div>
                <h3 class="text-2xl font-bold mb-4">Lebsa Zina</h3>
                <p class="text-sm text-gray-300">L’élégance algérienne à portée de main.</p>
            </div>
            <div>
                <h4 class="text-xl font-semibold mb-3">Navigation</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('landing-page') }}" class="text-gray-300 hover:text-brand-gold hover:underline">Accueil</a></li>
                    <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-brand-gold hover:underline">Catalogue (Prêt-à-Porter)</a></li>
                    <li><a href="{{ route('sur-mesure') }}" class="text-gray-300 hover:text-brand-gold hover:underline">Création Sur Mesure</a></li>
                    <li><a href="#services" class="text-gray-300 hover:text-brand-gold hover:underline">Nos Services</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xl font-semibold mb-3">Contact</h4>
                <ul class="text-sm space-y-2 text-gray-300">
                    <li>📍 Tlemcen, Algérie</li>
                    <li>📞 +213 555 123 456</li>
                    <li>✉️ contact@lebsazina.dz</li>
                </ul>
            </div>
        </div>
        <div class="text-center text-xs text-gray-400 mt-10 pt-8 border-t border-gray-700/50">
            © {{ date('Y') }} Lebsa Zina. Tous droits réservés.
        </div>
    </footer>

</body>
</html>