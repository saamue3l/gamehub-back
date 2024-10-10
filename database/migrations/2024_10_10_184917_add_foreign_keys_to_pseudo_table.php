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
        Schema::table('pseudo', function (Blueprint $table) {
            $table->foreign(['idPlateforme'], 'pseudo_ibfk_1')->references(['idPlateforme'])->on('plateforme')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['idUtilisateur'], 'pseudo_ibfk_2')->references(['idUtilisateur'])->on('utilisateur')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pseudo', function (Blueprint $table) {
            $table->dropForeign('pseudo_ibfk_1');
            $table->dropForeign('pseudo_ibfk_2');
        });
    }
};
