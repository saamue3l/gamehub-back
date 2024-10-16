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
        Schema::table('actionhistory', function (Blueprint $table) {
            $table->foreign(['userId'], 'actionhistory_ibfk_1')->references(['userId'])->on('user')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['actionId'], 'actionhistory_ibfk_2')->references(['actionId'])->on('action')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actionhistory', function (Blueprint $table) {
            $table->dropForeign('actionhistory_ibfk_1');
            $table->dropForeign('actionhistory_ibfk_2');
        });
    }
};
