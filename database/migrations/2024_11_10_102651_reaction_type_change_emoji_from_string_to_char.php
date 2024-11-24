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
        Schema::table('reactiontype', function (Blueprint $table) {
            $table->string('emoji', 4)->collation('utf8mb4_bin')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reactiontype', function (Blueprint $table) {
            $table->string('emoji', 1)->change();
        });
    }
};
