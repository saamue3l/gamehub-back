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
        Schema::create('pseudo', function (Blueprint $table) {
            $table->integer('idPseudo', true);
            $table->string('pseudo', 50);
            $table->integer('idPlateforme')->index('idplateforme');
            $table->integer('idUtilisateur')->index('idutilisateur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pseudo');
    }
};
