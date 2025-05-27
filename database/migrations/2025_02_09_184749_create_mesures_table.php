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
        Schema::create('mesures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modele_id')->constrained('modeles')->onDelete('cascade')->nullable();
            $table->string('label'); // Nom de la mesure (ex: "Tour de poitrine")
            $table->decimal('valeur_par_defaut', 8, 2); // Valeur par défaut
            $table->string('variable_xml'); // Correspondance avec XML
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesures');
    }
};
