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
        Schema::create('reaction', function (Blueprint $table) {
            $table->integer('idReaction', true);
            $table->integer('idTypeReaction')->index('idtypereaction');
            $table->integer('idUtilisateur')->index('idutilisateur');
            $table->integer('idPost')->index('idpost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reaction');
    }
};
