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
        Schema::table('jeufavori', function (Blueprint $table) {
            $table->foreign(['idPlateforme'], 'jeufavori_ibfk_1')->references(['idPlateforme'])->on('plateforme')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['idTypeCompetence'], 'jeufavori_ibfk_2')->references(['idTypeCompetence'])->on('typecompetence')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['idJeu'], 'jeufavori_ibfk_3')->references(['idJeu'])->on('jeu')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['idUtilisateur'], 'jeufavori_ibfk_4')->references(['idUtilisateur'])->on('utilisateur')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jeufavori', function (Blueprint $table) {
            $table->dropForeign('jeufavori_ibfk_1');
            $table->dropForeign('jeufavori_ibfk_2');
            $table->dropForeign('jeufavori_ibfk_3');
            $table->dropForeign('jeufavori_ibfk_4');
        });
    }
};
