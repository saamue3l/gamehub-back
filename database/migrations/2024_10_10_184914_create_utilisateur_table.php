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
        Schema::create('utilisateur', function (Blueprint $table) {
            $table->integer('idUtilisateur', true);
            $table->string('pseudo', 50)->unique('pseudo');
            $table->string('email', 100)->unique('email');
            $table->string('password');
            $table->text('photo')->nullable();
            $table->integer('xp')->nullable()->default(0);
            $table->integer('idStatut')->index('idstatut');
            $table->integer('idRole')->index('idrole');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateur');
    }
};
