<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('modeles', function (Blueprint $table) {
            $table->boolean('en_stock')->default(true)->after('xml');
        });
    }

    public function down(): void {
        Schema::table('modeles', function (Blueprint $table) {
            $table->dropColumn('en_stock');
        });
    }
};
