<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Nom de la conversation (par exemple, "Groupe A", ou autre)
            $table->timestamps();

                $table->index('updated_at');
        });

        Schema::create('conversation_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversationId')->constrained('conversations')->onDelete('cascade');
            $table->foreignId('userId')->constrained('user')->onDelete('cascade');
            $table->timestamps();

                $table->unique(['conversationId', 'userId']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('conversation_user');
        Schema::dropIfExists('conversations');
    }
};
