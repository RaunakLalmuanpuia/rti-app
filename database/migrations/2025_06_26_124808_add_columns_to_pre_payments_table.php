<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPrePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pre_payments', function (Blueprint $table) {
            $table->string  ('citizen_question_file')->after('citizen_question_department')->nullable();
            $table->boolean ('life_or_death')->after('citizen_question_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pre_payments', function (Blueprint $table) {
            $table->dropColumn('citizen_question_file');
            $table->dropColumn('life_or_death');
        });
    }
}
