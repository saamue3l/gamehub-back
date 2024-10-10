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
        Schema::create('succesobtenu', function (Blueprint $table) {
            $table->integer('idUtilisateur');
            $table->integer('idSucces')->index('idsucces');
            $table->date('dateObtention');

            $table->primary(['idUtilisateur', 'idSucces']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('succesobtenu');
    }
};
