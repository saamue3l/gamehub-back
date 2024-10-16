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
        Schema::create('topic', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);

            $table->foreignIdFor(\App\Models\User::class, "creatorId");
            $table->foreignIdFor(\App\Models\Forum::class, "forumId");
            $table->foreignIdFor(\App\Models\TopicStatus::class, "topicStatusId");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic');
    }
};
