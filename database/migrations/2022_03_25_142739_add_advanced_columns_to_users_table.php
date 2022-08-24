<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdvancedColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('birthday')->after('password')->nullable();
            $table->string('trello')->after('birthday')->nullable();
            $table->string('card')->after('trello')->nullable();
            $table->string('bank')->after('card')->nullable();
            $table->string('swift')->after('bank')->nullable();
            $table->string('address')->after('swift')->nullable();
            $table->string('timezone')->after('address')->nullable();
            $table->string('worktime')->after('timezone')->nullable();
            $table->string('startdate')->after('worktime')->nullable();
            $table->string('comments')->after('startdate')->nullable();
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
            $table->dropColumn([
                'birthday',
                'trello',
                'card',
                'bank',
                'swift',
                'address',
                'timezone',
                'worktime',
                'startdate',
                'comments'
            ]);
        });
    }
}
