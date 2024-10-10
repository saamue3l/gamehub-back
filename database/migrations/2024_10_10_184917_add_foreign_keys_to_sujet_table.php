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
        Schema::table('sujet', function (Blueprint $table) {
            $table->foreign(['idForum'], 'sujet_ibfk_1')->references(['idForum'])->on('forum')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['idUtilisateur'], 'sujet_ibfk_2')->references(['idUtilisateur'])->on('utilisateur')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['idStatutSujet'], 'sujet_ibfk_3')->references(['idStatutSujet'])->on('statutsujet')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sujet', function (Blueprint $table) {
            $table->dropForeign('sujet_ibfk_1');
            $table->dropForeign('sujet_ibfk_2');
            $table->dropForeign('sujet_ibfk_3');
        });
    }
};
