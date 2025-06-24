@extends('layouts.test2')

@section('content')
    <div class="bg-[#FFF8EC] mt-10 text-gray-800">

        {{-- Hero Section --}}
        <section class="bg-gradient-to-r from-[#05335E] to-[#021C35] text-[#FDFBF1] py-20 px-6 text-center">
            <div class="max-w-4xl mx-auto space-y-6">
                <h1 class="text-4xl md:text-5xl font-bold">Lebsa Zina</h1>
                <p class="text-lg md:text-xl">
                    Confectionnez votre tenue traditionnelle algérienne selon vos goûts, avec devis personnalisés et
                    génération automatique de patrons.
                </p>
                <a href="#personnalisation"
                    class="inline-flex items-center gap-2 bg-[#FDFBF1] text-[#05335E] px-6 py-3 rounded-lg text-lg font-medium hover:bg-[#F2F2F2] transition">
                    Commencer la personnalisation
                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
            </div>
        </section>

        {{-- À propos --}}
        <section class="py-16 px-6 bg-[#FDFBF1]">
            <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
                <img src="{{ asset('images/customisation.png') }}" alt="Personnalisation" class="rounded-xl ">
                <div>
                    <h2 class="text-3xl font-semibold mb-4 text-[#05335E]">L’innovation au service de la couture</h2>
                    <p class="text-black text-lg leading-relaxed">
                         <strong>Lebsa Zina</strong> est bien plus qu’un
                        simple site de personnalisation vestimentaire :
                        c’est une plateforme innovante conçue pour moderniser le processus de commande de vêtements
                        traditionnels sur mesure.
                        Grâce à une sélection dynamique de modules (manches, cols, coupes, longueurs…), elle permet aux
                        clientes de composer leur propre tenue en toute autonomie via une interface intuitive.
                        Les devis sont générés automatiquement et les patrons sont produits de manière intelligente selon
                        les choix effectués.
                        Pensé comme un outil clé-en-main, <strong>Lebsa Zina</strong> peut être adopté par des ateliers de
                        couture, des coopératives artisanales ou des porteurs de projet souhaitant digitaliser leur offre
                        tout en valorisant le patrimoine vestimentaire algérien.
                    </p>

                </div>
            </div>
        </section>

        {{-- Nos engagements --}}
        <section class="bg-[#05335E] py-20 px-6 text-[#FDFBF1]">
            <div class="max-w-6xl mx-auto text-center space-y-10">
                <h2 class="text-3xl font-bold mb-4">Nos engagements</h2>
                <p class="text-lg max-w-3xl mx-auto text-[#E6E6E6]">
                    Chez <strong>Lebsa Zina</strong>, nous nous engageons à offrir une expérience fiable, humaine et
                    innovante. Chaque tenue est pensée pour vous, avec soin et précision.
                </p>

                <div class="grid md:grid-cols-4 gap-8 text-left text-sm md:text-base">
                    <div class="bg-[#FFFFFF0D] rounded-xl p-6 border border-[#FFFFFF1A]">
                        <i data-lucide="check-circle" class="w-6 h-6 text-[#FFD700] mb-3"></i>
                        <p><strong>100% sur mesure</strong><br>Chaque modèle s’adapte à vos choix et vos mesures exactes.
                        </p>
                    </div>
                    <div class="bg-[#FFFFFF0D] rounded-xl p-6 border border-[#FFFFFF1A]">
                        <i data-lucide="clock" class="w-6 h-6 text-[#FFD700] mb-3"></i>
                        <p><strong>Réponse rapide</strong><br>Recevez votre devis personnalisé en un temps record.</p>
                    </div>
                    <div class="bg-[#FFFFFF0D] rounded-xl p-6 border border-[#FFFFFF1A]">
                        <i data-lucide="scissors" class="w-6 h-6 text-[#FFD700] mb-3"></i>
                        <p><strong>Patrons professionnels</strong><br>Validés par des couturières expérimentées.</p>
                    </div>
                    <div class="bg-[#FFFFFF0D] rounded-xl p-6 border border-[#FFFFFF1A]">
                        <i data-lucide="shield-check" class="w-6 h-6 text-[#FFD700] mb-3"></i>
                        <p><strong>Respect de votre vie privée</strong><br>Vos données et mesures sont traitées en toute
                            confidentialité.</p>
                    </div>
                </div>
            </div>
        </section>


        {{-- Composant Livewire QuestionnaireSelector --}}
        <section id="personnalisation" class="bg-[#FDFBF1] py-20 px-6">
            <div class="max-w-6xl mx-auto text-center mb-10">
                <h2 class="text-3xl font-bold text-[#05335E] mb-4">Créez votre tenue sur mesure</h2>
                <p class="text-[#5C4A31] text-lg">
                    Répondez au questionnaire ci-dessous pour trouver les modèles qui vous correspondent parfaitement.
                </p>
            </div>

            <div class="max-w-6xl mx-auto space-y-8">
                @livewire('questionnaire-selector')
            </div>
        </section>

        {{-- Avis clients --}}
        <section class="py-16 px-6 bg-[#FDFBF1]">
            <div class="max-w-5xl mx-auto text-center">
                <h2 class="text-3xl font-semibold mb-8 text-[#05335E]">Ce que disent nos clientes</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    @foreach (['Service rapide', 'Modèles élégants', 'Personnalisation parfaite'] as $avis)
                        <div class="bg-[#05335E] p-6 rounded-xl shadow-md">
                            <i data-lucide="stars" class="w-6 h-6 text-yellow-500 mb-3 mx-auto"></i>
                            <p class="text-[#FDFBF1] italic">"{{ $avis }}."</p>
                            <p class="mt-2 text-sm text-[#FDFBF1]">— Une cliente satisfaite</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="bg-[#05335E] text-[#FDFBF1] py-10">
            <div class="max-w-6xl mx-auto text-center space-y-4">
                <p>&copy; {{ date('Y') }} Lebsa Zina. Tous droits réservés.</p>
                <div class="flex justify-center gap-6">
                    <a href="#" class="hover:text-blue-300">Conditions d'utilisation</a>
                    <a href="#" class="hover:text-blue-300">Confidentialité</a>
                    <a href="#" class="hover:text-blue-300">Contact</a>
                </div>
            </div>
        </footer>
    </div>
@endsection
