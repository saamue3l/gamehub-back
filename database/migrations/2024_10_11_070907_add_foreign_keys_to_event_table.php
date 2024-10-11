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
        Schema::table('event', function (Blueprint $table) {
            $table->foreign(['userId'], 'event_ibfk_1')->references(['userId'])->on('user')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['gameId'], 'event_ibfk_2')->references(['gameId'])->on('game')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event', function (Blueprint $table) {
            $table->dropForeign('event_ibfk_1');
            $table->dropForeign('event_ibfk_2');
        });
    }
};
