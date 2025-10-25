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
            $table->id();

            $table->foreignIdFor(\App\Models\User::class, "userId");
            $table->foreignIdFor(\App\Models\ReactionType::class, "reactionTypeId");
            $table->foreignIdFor(\App\Models\Post::class, "postId");
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
