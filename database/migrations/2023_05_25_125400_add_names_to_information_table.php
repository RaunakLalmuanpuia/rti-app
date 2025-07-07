<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNamesToInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('information', function (Blueprint $table) {
            $table->string('aspio_name')->nullable();
            $table->string('aspio_contact')->nullable();
            $table->string('aspio_email')->nullable();

            $table->string('spio_name')->nullable();
            $table->string('spio_contact')->nullable();
            $table->string('spio_email')->nullable();

            $table->string('daa_name')->nullable();
            $table->string('daa_contact')->nullable();
            $table->string('daa_email')->nullable();

            $table->string('param1')->nullable();
            $table->string('param2')->nullable();
            $table->string('param3')->nullable();
            $table->string('param4')->nullable();
            $table->string('param5')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('information', function (Blueprint $table) {
            //
        });
    }
}
