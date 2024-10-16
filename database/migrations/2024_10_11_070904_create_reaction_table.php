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
            $table->integer('reactionId', true);
            $table->integer('reactionTypeId')->index('reaction_reactiontypeid_index');
            $table->integer('userId')->index('reaction_userid_index');
            $table->integer('postId')->index('reaction_postid_index');
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
