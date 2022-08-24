<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTestColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('full_position_title')->after('position_id')->nullable();
            $table->string('crypto_address')->after('card')->nullable();
            $table->string('fixed_salary')->after('swift')->nullable();
            $table->string('relocate_ready')->after('startdate')->nullable();
            $table->string('english_level')->after('relocate_ready')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('full_position_title');
            $table->dropColumn('crypto_address');
            $table->dropColumn('fixed_salary');
            $table->dropColumn('relocate_ready');
            $table->dropColumn('english_level');
        });
    }
}
