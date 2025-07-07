<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrePaytmPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_paytm_payments', function (Blueprint $table) {
            $table->id();

            $table->string  ('order_id')->nullable();
            $table->string  ('user_id')->nullable();
            $table->integer  ('citizen_question_department')->nullable();
            $table->text  ('citizen_question')->nullable();

            $table->string  ('citizen_question_file')->nullable();
            $table->boolean ('citizen_bpl')->nullable();
            $table->string  ('citizen_bpl_file')->nullable();
            $table->boolean ('life_or_death')->nullable();

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
        Schema::dropIfExists('pre_paytm_payments');
    }
}
