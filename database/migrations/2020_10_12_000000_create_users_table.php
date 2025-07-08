<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->integer('role')->default(0);
            $table->text('bio')->nullable();

            $table->string('contact')->unique();
            $table->unsignedBigInteger('department')->nullable();
            $table->text('address')->nullable();
            $table->string('sex')->nullable();
            $table->string('status')->nullable();//Reject//Accept

            $table->foreign('department')->references('id')->on('departments');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
