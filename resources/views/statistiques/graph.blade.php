@extends('layouts.admin')

@section('content')
<div class="w-full max-w-4xl mx-auto p-6 bg-white rounded-2xl shadow-xl">
  <div class="mb-6">
    <h2 class="text-xl font-bold text-gray-800">Statistiques mensuelles</h2>
    <p class="text-sm text-gray-500">Ventes & Commandes - Année 2025</p>
  </div>

  <div class="relative h-72">
    <canvas id="lineChart"></canvas>
  </div>
</div>
@endsection
