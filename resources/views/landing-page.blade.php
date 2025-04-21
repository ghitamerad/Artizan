<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts & Tailwind -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<div class="bg-white text-gray-800">

    <!-- Navigation -->
    <nav class="flex justify-between items-center px-8 py-4 shadow">
        <h1 class="text-2xl font-bold text-yellow-600">Lebsa Zina</h1>
        <div class="space-x-6">
            <a href="{{ route('panier') }}" class="hover:text-yellow-500 transition">Catalogue</a>
            <a href="#services" class="hover:text-yellow-500 transition">Services</a>
            <a href="#contact" class="hover:text-yellow-500 transition">Contact</a>
        </div>
    </nav>

    <!-- Section 1: Présentation -->
    <section class="flex flex-col md:flex-row items-center justify-between px-8 py-16 bg-yellow-50">
        <div class="md:w-1/2 space-y-6">
            <h2 class="text-4xl font-bold text-yellow-700">Bienvenue chez Lebsa Zina</h2>
            <p class="text-lg text-gray-700">Votre boutique spécialisée dans la location, l'achat et la confection sur mesure de tenues traditionnelles élégantes.</p>
            <a href="{{ route('panier') }}" class="inline-block bg-yellow-500 text-white px-6 py-2 rounded-full shadow hover:bg-yellow-600 transition">Voir les modèles</a>
        </div>
        <div class="md:w-1/2 mt-8 md:mt-0">
            <img src="images/dame.png" alt="Modèle femme" class="rounded-xl shadow-lg">
        </div>
    </section>

    <!-- Section 2: Nos services -->
    <section id="services" class="px-8 py-16 bg-white text-center">
        <h2 class="text-3xl font-bold text-yellow-700 mb-8">Nos Services</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-yellow-100 p-6 rounded-lg shadow hover:shadow-lg transition">
                <h3 class="text-xl font-semibold mb-2">Location de tenues</h3>
                <p>Louez des tenues traditionnelles pour vos événements spéciaux à petit prix.</p>
            </div>
            <div class="bg-yellow-100 p-6 rounded-lg shadow hover:shadow-lg transition">
                <h3 class="text-xl font-semibold mb-2">Vente de modèles uniques</h3>
                <p>Découvrez des créations originales réalisées par nos couturières partenaires.</p>
            </div>
            <div class="bg-yellow-100 p-6 rounded-lg shadow hover:shadow-lg transition">
                <h3 class="text-xl font-semibold mb-2">Confection sur mesure</h3>
                <p>Personnalisez votre tenue selon vos goûts et vos mesures exactes.</p>
            </div>
        </div>
    </section>

    <!-- Section 3: Derniers modèles en vogue -->
    <section id="contact" class="px-8 py-16 bg-yellow-50">
        <h2 class="text-3xl font-bold text-yellow-700 text-center mb-8">Derniers modèles en vogue</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-4">
                    <img src="" alt="" class="w-full h-64 object-cover rounded-lg mb-4">
                    <h3 class="text-lg font-semibold"></h3>
                    <p class="text-sm text-gray-600"></p>
                </div>
        </div>
    </section>

</div>
</html>
