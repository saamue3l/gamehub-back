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
        Schema::table('disponibilite', function (Blueprint $table) {
            $table->foreign(['idUtilisateur'], 'disponibilite_ibfk_1')->references(['idUtilisateur'])->on('utilisateur')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disponibilite', function (Blueprint $table) {
            $table->dropForeign('disponibilite_ibfk_1');
        });
    }
};
