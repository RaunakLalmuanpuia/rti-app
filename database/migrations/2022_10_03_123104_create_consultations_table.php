<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('information_id')->nullable();
            // $table->unsignedBigInteger('users_id')->nullable();
            $table->unsignedBigInteger('departments_id')->nullable();

            $table->text('original_spio_remark')->nullable();
            $table->dateTime('original_spio_remark_edited_at')->nullable();

            $table->text('comment')->nullable();
            $table->dateTime('comment_edited_at')->nullable();

            $table->string('file')->nullable();
            $table->dateTime('received_date')->nullable();
            $table->dateTime('answered_date')->nullable();

            $table->string('param1')->nullable();
            $table->string('param2')->nullable();
            $table->string('param3')->nullable();
            $table->string('param4')->nullable();
            $table->string('param5')->nullable();
            $table->string('param6')->nullable();
            $table->string('param7')->nullable();
            $table->string('param8')->nullable();
            $table->string('param9')->nullable();
            $table->string('param10')->nullable();

            $table->foreign('information_id')->references('id')->on('information');
            // $table->foreign('users_id')->references('id')->on('users');
            $table->foreign('departments_id')->references('id')->on('departments');


            $table->softDeletes();
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
        Schema::dropIfExists('consultations');
    }
}
