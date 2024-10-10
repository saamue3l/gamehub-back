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
        Schema::table('historiqueaction', function (Blueprint $table) {
            $table->foreign(['idUtilisateur'], 'historiqueaction_ibfk_1')->references(['idUtilisateur'])->on('utilisateur')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['idAction'], 'historiqueaction_ibfk_2')->references(['idAction'])->on('action')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historiqueaction', function (Blueprint $table) {
            $table->dropForeign('historiqueaction_ibfk_1');
            $table->dropForeign('historiqueaction_ibfk_2');
        });
    }
};
