@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-6">

        <div class="flex flex-wrap -mx-3 mb-8">

            <!-- Commandes validées -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 bg-white shadow-soft-xl rounded-2xl">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="w-2/3 px-3">
                                <p class="mb-0 text-sm font-semibold">Commandes validées</p>
                                <h5 class="mb-0 font-bold">
                                    {{ $commandesValidees }}
                                    <span
                                        class="text-sm font-bold {{ $tauxValidees >= 0 ? 'text-lime-500' : 'text-red-500' }}">
                                        ({{ $tauxValidees >= 0 ? '+' : '' }}{{ $tauxValidees }}%)
                                    </span>
                                </h5>
                            </div>
                            <div class="w-1/3 px-3 text-right">
                                <div
                                    class="inline-block w-12 h-12 bg-gradient-to-tl from-green-600 to-lime-400 rounded-lg text-white text-center">
                                    <i class="fas fa-check-circle text-lg relative top-3.5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commandes terminées -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 bg-white shadow-soft-xl rounded-2xl">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="w-2/3 px-3">
                                <p class="mb-0 text-sm font-semibold">Commandes terminées</p>
                                <h5 class="mb-0 font-bold">
                                    {{ $commandesTerminees }}
                                    <span
                                        class="text-sm font-bold {{ $tauxTerminees >= 0 ? 'text-lime-500' : 'text-red-500' }}">
                                        ({{ $tauxTerminees >= 0 ? '+' : '' }}{{ $tauxTerminees }}%)
                                    </span>
                                </h5>
                            </div>
                            <div class="w-1/3 px-3 text-right">
                                <div
                                    class="inline-block w-12 h-12 bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg text-white text-center">
                                    <i class="fas fa-box-check text-lg relative top-3.5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chiffre d'affaires -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 bg-white shadow-soft-xl rounded-2xl">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="w-2/3 px-3">
                                <p class="mb-0 text-sm font-semibold">Chiffre d'affaires</p>
                                <h5 class="mb-0 font-bold">
                                    {{ number_format($chiffreAffaires, 0, ',', ' ') }} DA
                                    <span
                                        class="text-sm font-bold {{ $tauxChiffreAffaires >= 0 ? 'text-lime-500' : 'text-red-500' }}">
                                        ({{ $tauxChiffreAffaires >= 0 ? '+' : '' }}{{ $tauxChiffreAffaires }}%)
                                    </span>
                                </h5>
                            </div>
                            <div class="w-1/3 px-3 text-right">
                                <div
                                    class="inline-block w-12 h-12 bg-gradient-to-tl from-purple-600 to-pink-400 rounded-lg text-white text-center">
                                    <i class="fas fa-coins text-lg relative top-3.5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nouveaux utilisateurs -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 bg-white shadow-soft-xl rounded-2xl">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="w-2/3 px-3">
                                <p class="mb-0 text-sm font-semibold">Nouveaux utilisateurs</p>
                                <h5 class="mb-0 font-bold">
                                    {{ $nouveauxUtilisateurs }}
                                </h5>
                            </div>
                            <div class="w-1/3 px-3 text-right">
                                <div
                                    class="inline-block w-12 h-12 bg-gradient-to-tl from-orange-500 to-yellow-300 rounded-lg text-white text-center">
                                    <i class="fas fa-users text-lg relative top-3.5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <!-- Diagrammes -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            <!-- Diagramme des commandes -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Statistiques mensuelles</h2>
                    <p class="text-sm text-gray-500">Prêt-à-porter & Sur-mesure - Année {{ $annee }}</p>
                </div>
                <div class="relative h-72">
                    <canvas id="lineChart3"></canvas>
                </div>
            </div>

            <!-- Histogramme des commandes couturière -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Commandes par couturière</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    @foreach ($couturieres as $c)
                        <div wire:click="changerCouturiere({{ $c->id }})" wire:key="couturiere-{{ $c->id }}"
                            class="cursor-pointer border rounded-xl p-4 shadow-sm transition hover:shadow-md
        {{ $selectedCouturiere === $c->id ? 'border-yellow-400 bg-yellow-50' : 'bg-white' }}">
                            <h3 class="text-md font-semibold text-gray-800">{{ $c->name }}</h3>
                        </div>
                    @endforeach
                </div>


                <div class="py-4 px-1 mb-4 bg-gray-900 rounded-xl h-72">
                    <canvas id="chart-bars" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>
        <!-- Meilleures ventes -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mt-12 p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="star" class="w-6 h-6 text-yellow-500"></i>
                Meilleures ventes
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full table-auto text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                        <tr>
                            <th class="py-3 px-4">Image</th>
                            <th class="py-3 px-4">Modèle</th>
                            <th class="py-3 px-4">Catégorie</th>
                            <th class="py-3 px-4">Prix</th>
                            <th class="py-3 px-4 text-right">Commandes</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 divide-y divide-gray-200">
                        @forelse($bestSellers as $modele)
                            <tr>
                                <td class="py-3 px-4">
                                    <img src="{{ asset('storage/' . $modele->image) }}"
                                        alt="Image modèle {{ $modele->nom }}"
                                        class="w-12 h-12 object-cover rounded-lg border border-gray-200 shadow-sm">
                                </td>
                                <td class="py-3 px-4 font-medium">{{ $modele->nom }}</td>
                                <td class="py-3 px-4">{{ $modele->categorie->nom ?? 'N/A' }}</td>
                                <td class="py-3 px-4">{{ number_format($modele->prix, 0, ',', ' ') }} DA</td>
                                <td class="py-3 px-4 text-right">{{ $modele->details_commandes_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 px-4 text-center text-gray-500">Aucun modèle trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let chartCouturiere;

        document.addEventListener('DOMContentLoaded', function() {
            // Line Chart - Commandes
            const ctx = document.getElementById('lineChart3').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                            label: 'Prêt-à-porter',
                            data: @json($pretAPorter),
                            fill: true,
                            tension: 0.4,
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            borderColor: '#6366f1',
                            pointBackgroundColor: '#6366f1',
                            pointBorderColor: '#fff'
                        },
                        {
                            label: 'Sur-mesure',
                            data: @json($surMesure),
                            fill: false,
                            tension: 0.4,
                            borderColor: '#facc15',
                            pointBackgroundColor: '#facc15',
                            pointBorderColor: '#fff'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Bar Chart - Couturière
            const ctxBar = document.getElementById("chart-bars")?.getContext("2d");
            if (ctxBar) {
                console.log("ctxBar", ctxBar);

                chartCouturiere = new Chart(ctxBar, {
                    type: "bar",
                    data: {
                        labels: @json($labels),
                        datasets: [{
                            label: "Commandes assignées",
                            data: @json($commandesParMoisCouturiere),
                            backgroundColor: "#facc15",
                            borderRadius: 6,
                            maxBarThickness: 30,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                ticks: {
                                    color: "#fff",
                                    font: {
                                        size: 12,
                                        family: "Open Sans",
                                    }
                                },
                                grid: {
                                    display: false,
                                },
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    color: "#fff",
                                    font: {
                                        size: 12,
                                        family: "Open Sans",
                                    }
                                },
                                grid: {
                                    drawBorder: false,
                                    display: true,
                                    color: "rgba(255,255,255,0.1)"
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: "#fff"
                                }
                            }
                        }
                    }
                });
            }

            // Mise à jour du graphique couturière après changement Livewire
            Livewire.on('updateChartCouturiere', (data) => {
                console.log("Nouvelle data couturière :", data);
                if (chartCouturiere) {
                    chartCouturiere.data.datasets[0].data = data;
                    chartCouturiere.update();
                }
            });


        });
    </script>
@endsection
