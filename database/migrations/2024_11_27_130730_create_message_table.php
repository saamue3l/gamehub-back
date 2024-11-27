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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            // Remarque : Nous remplaçons 'recipientId' par 'conversationId' pour relier le message à la conversation
            $table->foreignId('conversationId')->constrained('conversations')->onDelete('cascade');
            $table->foreignId('senderId')->constrained('user')->onDelete('cascade'); // Relier à la table 'user'
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
