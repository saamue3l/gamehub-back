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
        Schema::table('reaction', function (Blueprint $table) {
            $table->foreign(['idTypeReaction'], 'reaction_ibfk_1')->references(['idTypeReaction'])->on('typereaction')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['idUtilisateur'], 'reaction_ibfk_2')->references(['idUtilisateur'])->on('utilisateur')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['idPost'], 'reaction_ibfk_3')->references(['idPost'])->on('post')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reaction', function (Blueprint $table) {
            $table->dropForeign('reaction_ibfk_1');
            $table->dropForeign('reaction_ibfk_2');
            $table->dropForeign('reaction_ibfk_3');
        });
    }
};
