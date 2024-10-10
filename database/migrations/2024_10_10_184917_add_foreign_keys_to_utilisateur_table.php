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
        Schema::table('utilisateur', function (Blueprint $table) {
            $table->foreign(['idStatut'], 'utilisateur_ibfk_1')->references(['idStatut'])->on('statut')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['idRole'], 'utilisateur_ibfk_2')->references(['idRole'])->on('role')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utilisateur', function (Blueprint $table) {
            $table->dropForeign('utilisateur_ibfk_1');
            $table->dropForeign('utilisateur_ibfk_2');
        });
    }
};
