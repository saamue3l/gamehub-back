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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class, "userId")->constrained('user')->onDelete('cascade');
            $table->foreignId('typeId')->constrained('notification_types')->onDelete('cascade');
            $table->string('message');
            $table->timestamp('readAt')->nullable();
            $table->timestamp('processedAt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
