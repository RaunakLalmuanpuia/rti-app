<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->string('order_id')->unique();
            $table->string('tracking_id')->unique();
            $table->string('bank_ref_no')->nullable();
            $table->string('order_status')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('card_name')->nullable();
            $table->string('currency')->nullable();
            $table->string('amount')->nullable();
            $table->string('billing_name')->nullable();
            $table->string('discount_value')->nullable();
            $table->string('mer_amount')->nullable();
            $table->string('trans_date')->nullable(); 

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
        Schema::dropIfExists('payments');
    }
}


// 0 => "order_id=123654789"
//   1 => "tracking_id=310006982695"
//   2 => "bank_ref_no=1615445814398"
//   3 => "order_status=Success"
//   4 => "failure_message="
//   5 => "payment_mode=Net Banking"
//   6 => "card_name=AvenuesTest"
//   7 => "status_code=null"
//   8 => "status_message=Y"
//   9 => "currency=INR"
//   10 => "amount=1.00"
//   11 => "billing_name=" -> used
//   12 => "billing_address=" -> used
//   13 => "billing_city="
//   14 => "billing_state="
//   15 => "billing_zip="
//   16 => "billing_country="
//   17 => "billing_tel="        ->used
//   18 => "billing_email=“.   ->used
//   19 => "delivery_name="
//   20 => "delivery_address="
//   21 => "delivery_city="
//   22 => "delivery_state="
//   23 => "delivery_zip="
//   24 => "delivery_country="
//   25 => "delivery_tel="
//   26 => "merchant_param1=“.     ->department ID
//   27 => "merchant_param2=“.     ->  list of files attached in string.
//   28 => "merchant_param3=“.     -> question
//   29 => "merchant_param4=“.    ->life or death
//   30 => "merchant_param5="
//   31 => "vault=N"
//   32 => "offer_type=null"
//   33 => "offer_code=null"
//   34 => "discount_value=0.0"
//   35 => "mer_amount=1.00"
//   36 => "eci_value=null"
//   37 => "retry=N"
//   38 => "response_code=0"
//   39 => "billing_notes="
//   40 => "trans_date=11/03/2021 12:27:45"
//   41 => "bin_country="
// ]