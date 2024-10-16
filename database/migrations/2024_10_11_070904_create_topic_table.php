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
            $table->integer('topicId', true);
            $table->string('title', 200);
            $table->integer('forumId')->index('topic_forumid_index');
            $table->integer('userId')->index('topic_userid_index');
            $table->integer('topicStatusId')->index('topic_topicstatusid_index');
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
