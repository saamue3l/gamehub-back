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
        Schema::create('jeufavori', function (Blueprint $table) {
            $table->integer('idJeuFavori', true);
            $table->text('description')->nullable();
            $table->integer('idPlateforme')->index('idplateforme');
            $table->integer('idTypeCompetence')->index('idtypecompetence');
            $table->integer('idJeu')->index('idjeu');
            $table->integer('idUtilisateur')->index('idutilisateur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jeufavori');
    }
};
