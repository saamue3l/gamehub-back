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
        Schema::create('evenement', function (Blueprint $table) {
            $table->integer('idEvenement', true);
            $table->text('description')->nullable();
            $table->integer('nbJoueursMax');
            $table->timestamp('dateEvenement');
            $table->integer('idUtilisateur')->index('idutilisateur');
            $table->integer('idJeu')->index('idjeu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenement');
    }
};
