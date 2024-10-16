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
        Schema::table('topic', function (Blueprint $table) {
            $table->foreign(['forumId'], 'topic_ibfk_1')->references(['forumId'])->on('forum')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['userId'], 'topic_ibfk_2')->references(['userId'])->on('user')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['topicStatusId'], 'topic_ibfk_3')->references(['topicStatusId'])->on('topicstatus')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topic', function (Blueprint $table) {
            $table->dropForeign('topic_ibfk_1');
            $table->dropForeign('topic_ibfk_2');
            $table->dropForeign('topic_ibfk_3');
        });
    }
};
