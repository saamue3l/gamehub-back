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
        Schema::create('historiqueaction', function (Blueprint $table) {
            $table->integer('idUtilisateur');
            $table->integer('idAction')->index('idaction');
            $table->timestamp('dateAction')->useCurrent();

            $table->primary(['idUtilisateur', 'idAction']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historiqueaction');
    }
};
