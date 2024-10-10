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
        Schema::create('post', function (Blueprint $table) {
            $table->integer('idPost', true);
            $table->text('contenu');
            $table->timestamp('dateCreation')->nullable()->useCurrent();
            $table->integer('idUtilisateur')->index('idutilisateur');
            $table->integer('idSujet')->index('idsujet');
            $table->integer('idStatutPost')->index('idstatutpost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post');
    }
};
