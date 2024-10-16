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
        Schema::create('user', function (Blueprint $table) {
            $table->integer('userId', true);
            $table->string('username', 50)->unique('user_username_index');
            $table->string('email', 100)->unique('email');
            $table->string('password');
            $table->text('picture')->nullable();
            $table->integer('xp')->nullable()->default(0);
            $table->integer('statusId')->index('user_statusid_index');
            $table->integer('roleId')->index('user_roleid_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
