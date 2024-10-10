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
        Schema::create('sujet', function (Blueprint $table) {
            $table->integer('idSujet', true);
            $table->string('titre', 200);
            $table->integer('idForum')->index('idforum');
            $table->integer('idUtilisateur')->index('idutilisateur');
            $table->integer('idStatutSujet')->index('idstatutsujet');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sujet');
    }
};
