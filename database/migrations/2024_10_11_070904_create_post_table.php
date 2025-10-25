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
        Schema::create('post', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->timestamp('creationDate')->nullable()->useCurrent();

            $table->foreignIdFor(\App\Models\User::class, "userId");
            $table->foreignIdFor(\App\Models\Topic::class, "topicId");
            $table->foreignIdFor(\App\Models\PostStatus::class, "postStatusId");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post');
    }
};
