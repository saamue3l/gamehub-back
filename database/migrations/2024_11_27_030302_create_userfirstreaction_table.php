<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_first_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class, "userId");
            $table->foreignIdFor(\App\Models\Post::class, "postId");
            $table->timestamps();

            $table->unique(['userId', 'postId']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_first_reactions');
    }
};
