<?php

namespace App\Traits;

use Carbon\Carbon;
use Razorpay\Api\Api;

trait CanPay
{
    public function initiatePayment(){

        $d = Carbon::now()->timestamp; // Produces something like 1552296328

        $d = $d . rand(1000, 9999);
        $doubleRandom = rand(1000, 9999);
        $MY_ORDER_ID = $d . $doubleRandom;

        $orderId = 'RTI' . $MY_ORDER_ID;

        $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));

        $orderData = [
            'receipt'         => $orderId,
            'amount'          => 10 * 100, // amount in paise
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        $razorpayOrder = $api->order->create($orderData);

//        dd($razorpayOrder);
        if ($razorpayOrder['status'] === 'created') {
            return $razorpayOrder;
        }

        throw new \Exception($razorpayOrder['error']['description'],500);
    }
    public function initiatePaymentAttachment($amount){
        $d = Carbon::now()->timestamp; // Produces something like 1552296328

        $d = $d . rand(1000, 9999);
        $doubleRandom = rand(1000, 9999);
        $MY_ORDER_ID = $d . $doubleRandom;

        $orderId = 'ATTACH' . $MY_ORDER_ID;

        $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));

        $orderData = [
            'receipt'         => $orderId,
            'amount'          => $amount * 100, // amount in paise
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        $razorpayOrder = $api->order->create($orderData);

        if ($razorpayOrder['status'] === 'created') {
            return $razorpayOrder;
        }

        throw new \Exception($razorpayOrder['error']['description'],500);

    }
    public function verifyPayment($paymentId){

        $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));

        $data = $api->payment->fetch($paymentId);

        return $data;

    }
}
