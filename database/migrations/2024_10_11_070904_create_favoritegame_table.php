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
            $table->integer('platformId')->index('favoritegame_platformid_index');
            $table->integer('skillTypeId')->index('favoritegame_skilltypeid_index');
            $table->integer('gameId')->index('favoritegame_gameid_index');
            $table->integer('userId')->index('favoritegame_userid_index');
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
