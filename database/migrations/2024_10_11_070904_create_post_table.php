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
            $table->integer('postId', true);
            $table->text('content');
            $table->timestamp('creationDate')->nullable()->useCurrent();
            $table->integer('userId')->index('userid');
            $table->integer('topicId')->index('topicid');
            $table->integer('postStatusId')->index('poststatusid');
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
