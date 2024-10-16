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
        Schema::create('event', function (Blueprint $table) {
            $table->integer('eventId', true);
            $table->text('description')->nullable();
            $table->integer('maxPlayers');
            $table->timestamp('eventDate');
            $table->integer('userId')->index('userid');
            $table->integer('gameId')->index('gameid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event');
    }
};
