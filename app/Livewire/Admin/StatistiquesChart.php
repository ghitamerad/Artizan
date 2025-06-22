<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\DetailCommande;
use App\Models\Modele;
use Livewire\Attributes\On; // si besoin
use App\Models\User;
use Illuminate\Support\Facades\Log;


class StatistiquesChart extends Component
{
    public $labels = [];
    public $pretAPorter = [];
    public $surMesure = [];
    public $annee = 2025;

    public $commandesValidees;
    public $commandesTerminees;
    public $chiffreAffaires;
    public $nouveauxUtilisateurs;

    public $tauxValidees;
    public $tauxTerminees;
    public $tauxChiffreAffaires;

    public $selectedCouturiere = null;
    public $couturieres = [];
    public $commandesParMoisCouturiere = [];

    public $bestSellers;


    public function mount()
    {
        $couturieres = User::where('role', 'couturiere')->get();
        $this->couturieres = $couturieres;
        $this->selectedCouturiere = $couturieres->first()?->id;
        $this->annee = now()->year;
        $this->loadData();
    }

public function changerCouturiere($id)
{
    $this->selectedCouturiere = $id;
    Log::info("Couturière changée via clic : " . $id);

    $this->loadData();

    $this->dispatch('updateChartCouturiere', $this->commandesParMoisCouturiere);
}



    public function updatedAnnee()
    {
        $this->loadData();
    }




    public function loadData()
    {
        $this->labels = collect(range(1, 6))->map(function ($month) {
            return Carbon::create()->month($month)->locale('fr_FR')->isoFormat('MMM');
        })->toArray();

        $this->pretAPorter = [];
        $this->surMesure = [];

        foreach (range(1, 6) as $month) {
            $this->pretAPorter[] = DetailCommande::whereHas('commande', function ($q) use ($month) {
                $q->whereMonth('created_at', $month)
                    ->whereYear('created_at', $this->annee);
            })
                ->whereHas('modele', function ($q) {
                    $q->where('sur_commande', false);
                })
                ->count();

            $this->surMesure[] = DetailCommande::whereHas('commande', function ($q) use ($month) {
                $q->whereMonth('created_at', $month)
                    ->whereYear('created_at', $this->annee);
            })
                ->whereHas('modele', function ($q) {
                    $q->where('sur_commande', true);
                })
                ->count();
        }

        $moisActuel = Carbon::now()->month;
        $moisPrecedent = Carbon::now()->subMonth()->month;
        $anneeActuelle = Carbon::now()->year;
        $anneePrecedente = Carbon::now()->subMonth()->year;

        // Commandes validées : on utilise updated_at car elles sont validées après création
        $valideesActuel = DB::table('commandes')
            ->where('statut', 'validee')
            ->whereRaw('MONTH(created_at) = ?', [$moisActuel])
            ->whereRaw('YEAR(created_at) = ?', [$this->annee])
            ->count();


        $valideesPrec = DB::table('commandes')
            ->where('statut', 'validee')
            ->whereMonth('updated_at', $moisPrecedent)
            ->whereYear('updated_at', $anneePrecedente)
            ->count();

        // Commandes terminées : toujours updated_at
        $termineesActuel = DB::table('commandes')
            ->where('statut', 'expediee')
            ->whereMonth('updated_at', $moisActuel)
            ->whereYear('updated_at', $anneeActuelle)
            ->count();

        $termineesPrec = DB::table('commandes')
            ->where('statut', 'expediee')
            ->whereMonth('updated_at', $moisPrecedent)
            ->whereYear('updated_at', $anneePrecedente)
            ->count();

        // Chiffre d'affaires
        $caActuel = DB::table('commandes')
            ->where('statut', 'expediee')
            ->whereMonth('updated_at', $moisActuel)
            ->whereYear('updated_at', $anneeActuelle)
            ->sum('montant_total');

        $caPrec = DB::table('commandes')
            ->where('statut', 'expediee')
            ->whereMonth('updated_at', $moisPrecedent)
            ->whereYear('updated_at', $anneePrecedente)
            ->sum('montant_total');

        // Globales
        $this->commandesValidees = $valideesActuel;
        $this->commandesTerminees = $termineesActuel;
        $this->chiffreAffaires = $caActuel;
        $this->nouveauxUtilisateurs = DB::table('users')->whereYear('created_at', $this->annee)->count();

        // Taux
        $this->tauxValidees = $valideesPrec > 0 ? round((($valideesActuel - $valideesPrec) / $valideesPrec) * 100) : 0;
        $this->tauxTerminees = $termineesPrec > 0 ? round((($termineesActuel - $termineesPrec) / $termineesPrec) * 100) : 0;
        $this->tauxChiffreAffaires = $caPrec > 0 ? round((($caActuel - $caPrec) / $caPrec) * 100) : 0;



        // Réinitialiser les données
        $this->commandesParMoisCouturiere = [];

        foreach (range(1, 6) as $month) {
            $this->commandesParMoisCouturiere[] = DetailCommande::where('user_id', $this->selectedCouturiere)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $this->annee)
                ->count();
        }

        $this->bestSellers = Modele::withCount('detailsCommandes')
            ->orderByDesc('details_commandes_count')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.statistiques-chart')->layout('layouts.admin');
    }
}
