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
        Schema::create('availability', function (Blueprint $table) {
            $table->integer('availabilityId', true);
            $table->string('dayOfWeek', 20);
            $table->boolean('morning')->nullable()->default(false);
            $table->boolean('afternoon')->nullable()->default(false);
            $table->boolean('evening')->nullable()->default(false);
            $table->boolean('night')->nullable()->default(false);
            $table->integer('userId')->index('userid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availability');
    }
};
