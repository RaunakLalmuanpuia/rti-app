<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_payments', function (Blueprint $table) {
            $table->id();
            
            $table->string  ('citizen_name');
            $table->string  ('citizen_contact',30);
            $table->string  ('citizen_address')->nullable();

            $table->text  ('citizen_question')->nullable();
            $table->string  ('citizen_question_department')->nullable();

            $table->string  ('order_id')->unique();
            $table->string  ('amount');

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
        Schema::dropIfExists('pre_payments');
    }
}
