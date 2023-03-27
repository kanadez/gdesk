<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationIdToYmapsMarkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ymaps_markers', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->after('id');
        });

        Schema::table('ymaps_markers', function(Blueprint $table) {
            //$table->index('location_id'); // нельзя прицепить индекс если данные в этой таблице уже существуют
            //$table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ymaps_markers', function (Blueprint $table) {
            $table->dropColumn('location_id');
        });
    }
}
