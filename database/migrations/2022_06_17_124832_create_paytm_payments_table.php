<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaytmPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paytm_payments', function (Blueprint $table) {
            $table->id();
            
            $table->string('transactionId');
            $table->string('orderId');
            $table->string('currency');
            $table->string('status');
            $table->string('amount');
      
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
        Schema::dropIfExists('paytm_payments');
    }
}
