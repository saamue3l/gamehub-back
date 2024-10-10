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
        Schema::table('evenement', function (Blueprint $table) {
            $table->foreign(['idUtilisateur'], 'evenement_ibfk_1')->references(['idUtilisateur'])->on('utilisateur')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['idJeu'], 'evenement_ibfk_2')->references(['idJeu'])->on('jeu')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evenement', function (Blueprint $table) {
            $table->dropForeign('evenement_ibfk_1');
            $table->dropForeign('evenement_ibfk_2');
        });
    }
};
