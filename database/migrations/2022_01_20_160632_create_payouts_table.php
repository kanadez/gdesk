<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');
            $table->foreignId("category_id")->nullable()->references("id")->on("categories")->onDelete("cascade");
            $table->foreignId('user_id')->references("id")->on("users")->onDelete("cascade");
            $table->string('address');
            $table->string('external_id')->nullable();
            $table->float('amount');
            $table->float('fee')->default(0);
            $table->string('status');
            $table->string('paysystem_status')->nullable();
            $table->string('comment')->default('');
            $table->longText('payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payouts');
    }
}
