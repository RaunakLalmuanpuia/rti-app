<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaidAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paid_attachments', function (Blueprint $table) {
            $table->id();
            $table->integer('information_id')->nullable();
            $table->string('order_id')->nullable();
            $table->string('user_id')->nullable();

            $table->string('amount')->nullable();
            $table->string('attachment_name')->nullable();
            $table->string('payment_status')->nullable();

            $table->string('param1')->nullable();
            $table->string('param2')->nullable();
            $table->string('param3')->nullable();
            $table->string('param4')->nullable();
            $table->string('param5')->nullable();

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
        Schema::dropIfExists('paid_attachments');
    }
}
