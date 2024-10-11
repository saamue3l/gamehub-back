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
        Schema::table('reaction', function (Blueprint $table) {
            $table->foreign(['reactionTypeId'], 'reaction_ibfk_1')->references(['reactionTypeId'])->on('reactiontype')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['userId'], 'reaction_ibfk_2')->references(['userId'])->on('user')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['postId'], 'reaction_ibfk_3')->references(['postId'])->on('post')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reaction', function (Blueprint $table) {
            $table->dropForeign('reaction_ibfk_1');
            $table->dropForeign('reaction_ibfk_2');
            $table->dropForeign('reaction_ibfk_3');
        });
    }
};
