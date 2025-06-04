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
        Schema::create('modeles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('cascade');
            $table->string('nom'); // Nom du modèle
            $table->text('description')->nullable(); // Description facultative
            $table->integer('prix'); // Description facultative
            $table->string('patron')->nullable(); // Ajout du champ pour le patron
            $table->enum('type',['normal', 'fragment'])->default('normal'); // Ajout du champ pour le patron
            $table->text('xml')->nullable(); // Fiche de mesures sous forme XML
            $table->boolean('stock')->default(true);
            $table->boolean('sur_commande')->default(false);
            $table->string('image')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modeles');
    }
};
