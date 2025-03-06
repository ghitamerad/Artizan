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
        Schema::create('detail_commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->nullable();
            $table->foreignId('modele_id')->constrained('modeles')->onDelete('cascade');
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire', 10, 2);
            $table->string('fichier_patron')->nullable(); // Lien vers le fichier du patron
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_commandes');
    }
};
