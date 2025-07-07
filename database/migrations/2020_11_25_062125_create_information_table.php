<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformationTable extends Migration
{
    public function up()
    {
        Schema::create('information', function (Blueprint $table) {
          
            $table->id();
            $table->string  ('user_id')->nullable();

            $table->string  ('citizen_name');
            $table->string  ('citizen_contact',30);
            $table->string  ('citizen_address')->nullable();
            $table->text  ('citizen_question')->nullable();
            $table->string  ('citizen_question_file')->nullable();
            $table->unsignedBigInteger  ('citizen_question_department')->nullable();

            $table->string  ('order_id')->nullable()->unique();
            $table->string  ('tracking_id')->nullable()->unique();
            $table->string  ('bank_ref_no')->nullable()->unique();

            $table->boolean ('citizen_bpl')->nullable();
            $table->string  ('citizen_bpl_file')->nullable();
            $table->boolean ('life_or_death')->nullable();

            $table->dateTime    ('aspio_in')->nullable();
            $table->text      ('aspio_answer')->nullable();
            $table->text      ('aspio_id')->nullable();

            $table->dateTime    ('spio_in')->nullable();
            $table->text      ('spio_answer')->nullable();
            $table->string      ('spio_answer_file')->nullable();
            $table->text     ('spio_transfer_remark')->nullable();
            $table->string      ('spio_transfer_department')->nullable();
            $table->dateTime    ('spio_out')->nullable();
            $table->text      ('spio_id')->nullable();

            $table->dateTime    ('first_appeal_daa_in')->nullable();
            $table->text      ('first_appeal_citizen_question')->nullable();
            $table->string      ('first_appeal_citizen_question_file')->nullable();
            $table->text      ('first_appeal_daa_answer')->nullable();
            $table->string      ('first_appeal_daa_answer_file')->nullable();
            $table->dateTime    ('first_appeal_daa_out')->nullable();
            $table->text      ('daa_id')->nullable();

            $table->dateTime    ('second_appeal_cic_in')->nullable();
            $table->text      ('second_appeal_citizen_question')->nullable();
            $table->string      ('second_appeal_citizen_question_file')->nullable();
            $table->text      ('second_appeal_cic_answer')->nullable();
            $table->string      ('second_appeal_cic_answer_file')->nullable();
            $table->dateTime    ('second_appeal_cic_out')->nullable();
            
            $table->text     ('complain')->nullable();
            $table->text     ('transfer')->nullable();

            $table->string      ('secondhand_question_previous_department')->nullable();
            $table->text      ('secondhand_question_previous_department_remark')->nullable();
        
            $table->foreign('citizen_question_department')->references('id')->on('departments');

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
        Schema::dropIfExists('information');
    }
}
