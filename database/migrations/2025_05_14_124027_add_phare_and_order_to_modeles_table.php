<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('modeles', function (Blueprint $table) {
            // Remplacez 'prix' par le nom d'une colonne qui existe déjà dans votre table 'modeles'
            // si 'prix' n'est pas la bonne colonne après laquelle vous voulez ajouter 'est_phare'.
            $table->boolean('est_phare')->default(false)->after('prix'); 
            $table->integer('ordre_affichage')->nullable()->default(0)->after('est_phare');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modeles', function (Blueprint $table) {
            $table->dropColumn('ordre_affichage');
            $table->dropColumn('est_phare');
        });
    }
};