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
            $table->id();
            $table->string('username', 50);

            $table->foreignIdFor(\App\Models\User::class, "userId");
            $table->foreignIdFor(\App\Models\Platform::class, "platformId");
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
