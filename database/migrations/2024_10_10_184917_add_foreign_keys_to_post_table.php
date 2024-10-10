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
        Schema::table('post', function (Blueprint $table) {
            $table->foreign(['idUtilisateur'], 'post_ibfk_1')->references(['idUtilisateur'])->on('utilisateur')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['idSujet'], 'post_ibfk_2')->references(['idSujet'])->on('sujet')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['idStatutPost'], 'post_ibfk_3')->references(['idStatutPost'])->on('statutpost')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post', function (Blueprint $table) {
            $table->dropForeign('post_ibfk_1');
            $table->dropForeign('post_ibfk_2');
            $table->dropForeign('post_ibfk_3');
        });
    }
};
