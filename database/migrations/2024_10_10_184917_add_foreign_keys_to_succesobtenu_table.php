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
        Schema::table('succesobtenu', function (Blueprint $table) {
            $table->foreign(['idUtilisateur'], 'succesobtenu_ibfk_1')->references(['idUtilisateur'])->on('utilisateur')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['idSucces'], 'succesobtenu_ibfk_2')->references(['idSucces'])->on('succes')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('succesobtenu', function (Blueprint $table) {
            $table->dropForeign('succesobtenu_ibfk_1');
            $table->dropForeign('succesobtenu_ibfk_2');
        });
    }
};
