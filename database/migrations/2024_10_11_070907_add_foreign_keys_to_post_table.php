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
        Schema::table('post', function (Blueprint $table) {
            $table->foreign(['userId'], 'post_ibfk_1')->references(['userId'])->on('user')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['topicId'], 'post_ibfk_2')->references(['topicId'])->on('topic')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['postStatusId'], 'post_ibfk_3')->references(['postStatusId'])->on('poststatus')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post', function (Blueprint $table) {
            $table->dropForeign('post_ibfk_1');
            $table->dropForeign('post_ibfk_2');
            $table->dropForeign('post_ibfk_3');
        });
    }
};
