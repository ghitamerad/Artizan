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
        Schema::create('element_patrons', function (Blueprint $table) {
            $table->id();
            $table->string('fichier_patron');
            $table->foreignId('categorie_id')->constrained()->onDelete('cascade');
            $table->foreignId('attribut_valeur_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('element_patrons');
    }
};
