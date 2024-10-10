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
        Schema::table('participation', function (Blueprint $table) {
            $table->foreign(['idEvenement'], 'participation_ibfk_1')->references(['idEvenement'])->on('evenement')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['idUtilisateur'], 'participation_ibfk_2')->references(['idUtilisateur'])->on('utilisateur')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participation', function (Blueprint $table) {
            $table->dropForeign('participation_ibfk_1');
            $table->dropForeign('participation_ibfk_2');
        });
    }
};
