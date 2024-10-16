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
        Schema::create('actionhistory', function (Blueprint $table) {
            $table->integer('userId');
            $table->integer('actionId')->index('actionhistory_actionid_index');
            $table->timestamp('actionDate')->useCurrent();

            $table->primary(['userId', 'actionId']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actionhistory');
    }
};
