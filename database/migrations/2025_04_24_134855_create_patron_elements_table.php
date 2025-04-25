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
        Schema::create('patron_elements', function (Blueprint $table) {
            $table->id();
            $table->string('type');  // Type de l'élément (col, manche, bas, etc.)
            $table->string('nom');   // Nom de l'élément (ex: col rond, manche courte)
            $table->string('fichier_val');  // Chemin vers le fichier .val du patron
            $table->enum('zone', ['haut', 'bas', 'complet']);  // Zone du corps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patron_elements');
    }
};
