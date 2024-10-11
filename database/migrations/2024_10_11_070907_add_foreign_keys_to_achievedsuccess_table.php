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
        Schema::table('achievedsuccess', function (Blueprint $table) {
            $table->foreign(['userId'], 'achievedsuccess_ibfk_1')->references(['userId'])->on('user')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['successId'], 'achievedsuccess_ibfk_2')->references(['successId'])->on('success')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('achievedsuccess', function (Blueprint $table) {
            $table->dropForeign('achievedsuccess_ibfk_1');
            $table->dropForeign('achievedsuccess_ibfk_2');
        });
    }
};
