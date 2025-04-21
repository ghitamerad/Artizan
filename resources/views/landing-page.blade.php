<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lebsa Zina</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fdfaf7;
            font-family: 'Georgia', serif;
        }
    </style>
</head>
<body class="text-gray-800">

    <!-- HERO / INTRO -->
    <section class="bg-[#fdfaf7] min-h-screen flex items-center justify-center pt-10">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 items-center gap-10">

            <!-- Colonne gauche : texte -->
            <div class="space-y-6">
                <!-- Logo -->
                <h1 class="text-5xl font-extrabold text-yellow-800">Lebsa Zina</h1>

                <!-- Titre & description -->
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Bienvenue chez <span class="text-yellow-700">Lebsa Zina</span></h2>
                <p class="text-lg text-gray-700 max-w-md">L’élégance de la tradition algérienne revisitée. Découvrez notre collection unique à la vente, à la location ou en sur-mesure.</p>

                <!-- Bouton -->
                <a href="{{ route('home') }}" class="inline-block bg-yellow-700 hover:bg-yellow-800 text-white py-3 px-6 rounded-full shadow-md transition duration-300">
                    Voir les modèles
                </a>
            </div>

            <!-- Colonne droite : image -->
            <div class="flex justify-center md:justify-end">
                <img src="{{ asset('images/dame.png') }}" alt="Dame élégante" class="w-[380px] md:w-[500px] object-contain rounded-lg">
            </div>
        </div>
    </section>

    <!-- NOS SERVICES -->
    <section class="bg-white py-20" id="services">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-3xl font-semibold text-center mb-14 text-yellow-800">Nos Services</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
                <div class="space-y-4">
                    <img src="{{ asset('icons/dress.svg') }}" class="mx-auto h-14" alt="Vente">
                    <h3 class="text-xl font-medium text-yellow-900">Vente</h3>
                </div>
                <div class="space-y-4">
                    <img src="{{ asset('icons/hanger.svg') }}" class="mx-auto h-14" alt="Location">
                    <h3 class="text-xl font-medium text-yellow-900">Location</h3>
                </div>
                <div class="space-y-4">
                    <img src="{{ asset('icons/needle.svg') }}" class="mx-auto h-14" alt="Sur-mesure">
                    <h3 class="text-xl font-medium text-yellow-900">Sur-mesure</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-yellow-800 text-white py-12">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-2xl font-bold mb-4">Lebsa Zina</h3>
                <p>L’élégance algérienne à portée de main.</p>
            </div>
            <div>
                <h4 class="text-xl font-semibold mb-2">Navigation</h4>
                <ul class="space-y-1 text-sm">
                    <li><a href="#" class="hover:underline">Accueil</a></li>
                    <li><a href="{{ route('home') }}" class="hover:underline">Catalogue</a></li>
                    <li><a href="#services" class="hover:underline">Services</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xl font-semibold mb-2">Contact</h4>
                <ul class="text-sm space-y-1">
                    <li>📍 Tlemcen, Algérie</li>
                    <li>📞 +213 555 123 456</li>
                    <li>✉️ contact@lebsazina.dz</li>
                </ul>
            </div>
        </div>
        <div class="text-center text-sm text-gray-300 mt-10">© 2025 Lebsa Zina. Tous droits réservés.</div>
    </footer>

</body>
</html>
