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
        Schema::create('username', function (Blueprint $table) {
            $table->integer('usernameId', true);
            $table->string('username', 50);
            $table->integer('platformId')->index('platformid');
            $table->integer('userId')->index('userid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('username');
    }
};
