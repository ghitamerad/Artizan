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
    Schema::table('mesures', function (Blueprint $table) {
        $table->decimal('min', 6, 2)->default(0);
        $table->decimal('max', 6, 2)->default(250)->after('min');
    });
}

public function down(): void
{
    Schema::table('mesures', function (Blueprint $table) {
        $table->dropColumn(['min', 'max']);
    });
}

};
