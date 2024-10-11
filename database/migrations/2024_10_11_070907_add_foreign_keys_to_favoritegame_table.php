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
        Schema::table('favoritegame', function (Blueprint $table) {
            $table->foreign(['platformId'], 'favoritegame_ibfk_1')->references(['platformId'])->on('platform')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['skillTypeId'], 'favoritegame_ibfk_2')->references(['skillTypeId'])->on('skilltype')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['gameId'], 'favoritegame_ibfk_3')->references(['gameId'])->on('game')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['userId'], 'favoritegame_ibfk_4')->references(['userId'])->on('user')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favoritegame', function (Blueprint $table) {
            $table->dropForeign('favoritegame_ibfk_1');
            $table->dropForeign('favoritegame_ibfk_2');
            $table->dropForeign('favoritegame_ibfk_3');
            $table->dropForeign('favoritegame_ibfk_4');
        });
    }
};
