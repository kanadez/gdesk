<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOptionsSortColumnToPaysystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paysystems', function (Blueprint $table) {
            $table->longText('options')->nullable();
            $table->unsignedSmallInteger('sort_num')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paysystems', function (Blueprint $table) {
            $table->dropColumn('options');
            $table->dropColumn('sort_num');
        });
    }
}
