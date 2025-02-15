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
        Schema::create('mesure_detail_commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mesure_id')->constrained('mesures')->onDelete('cascade');
            $table->foreignId('details_commande_id')->constrained('detail_commandes')->onDelete('cascade');
            $table->decimal('valeur_mesure', 8, 2); // Valeur réelle saisie par le client
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesure_detail_commandes');
    }
};
