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
        // temporaly Remove fk constraints
        Schema::disableForeignKeyConstraints();

        Schema::table("post", function (Blueprint $table) {
            $table->foreign("topicId")->references("id")->on("topic")->onDelete("cascade")->onUpdate("cascade");
        });

        Schema::table("topic", function (Blueprint $table) {
            $table->foreign("forumId")->references("id")->on("forum")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("creatorId")->references("id")->on("user")->onDelete("cascade")->onUpdate("cascade");
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("post", function (Blueprint $table) {
            $table->dropForeign(["topicId"]);
        });

        Schema::table("topic", function (Blueprint $table) {
            $table->dropForeign(["forumId"]);
            $table->dropForeign(["creatorId"]);
        });
    }
};
