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
        Schema::create('disponibilite', function (Blueprint $table) {
            $table->integer('idDisponibilite', true);
            $table->string('jourSemaine', 20);
            $table->boolean('matin')->nullable()->default(false);
            $table->boolean('apresMidi')->nullable()->default(false);
            $table->boolean('soir')->nullable()->default(false);
            $table->boolean('nuit')->nullable()->default(false);
            $table->integer('idUtilisateur')->index('idutilisateur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disponibilite');
    }
};
