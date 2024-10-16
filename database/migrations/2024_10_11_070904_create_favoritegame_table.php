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
        Schema::create('favoritegame', function (Blueprint $table) {
            $table->integer('favoriteGameId', true);
            $table->text('description')->nullable();
            $table->integer('platformId')->index('platformid');
            $table->integer('skillTypeId')->index('skilltypeid');
            $table->integer('gameId')->index('gameid');
            $table->integer('userId')->index('userid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favoritegame');
    }
};
