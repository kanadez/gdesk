<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paysystem_id')->references('id')->on('paysystems')->onDelete('cascade');
            $table->string('currency');
            $table->string('title');
            $table->string('icon');
            $table->string('pgroup');
            $table->string('alias');
            $table->boolean('is_active');
            $table->float("min")->nullable();
            $table->float("max")->nullable();
            $table->text("options")->nullable();
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
        Schema::dropIfExists('payment_methods');
    }
}
