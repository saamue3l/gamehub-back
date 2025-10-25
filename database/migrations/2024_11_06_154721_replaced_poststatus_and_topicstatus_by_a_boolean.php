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
        Schema::table("topic", function (Blueprint $table) {
            $table->dropColumn("topicStatusId");
            $table->boolean("visible")->default(true);
        });
        Schema::table("post", function (Blueprint $table) {
            $table->dropColumn("postStatusId");
            $table->boolean("visible")->default(true);
        });
        Schema::dropIfExists("poststatus");
        Schema::dropIfExists("topicstatus");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
