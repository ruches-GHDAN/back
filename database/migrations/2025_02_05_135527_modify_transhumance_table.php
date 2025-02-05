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
        Schema::table('transhumances', function (Blueprint $table) {
            $table->dropColumn('locate');
            $table->double('latitude');
            $table->double('longitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('transhumances', function (Blueprint $table) {
            $table->string('locate');
            $table->dropColumn('oldLatitude');
            $table->dropColumn('oldLongitude');
        });
    }
};
