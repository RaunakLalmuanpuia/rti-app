<?php

namespace App\Http\Controllers;

use App\Models\payments;
use App\Models\PrePayment;
use App\Repositories\InformationRepository;
use App\Traits\CanPay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentCallbackController extends Controller
{
    //
    use CanPay;

    private InformationRepository $informationRepository;

    public function __construct(InformationRepository $informationRepository)
    {
        $this->repository = $informationRepository;
    }

    public function callback(Request $request)
    {

        $payment_id = $request->razorpay_payment_id;
        $order_id = $request->razorpay_order_id;


        $existingPayment = payments::where('tracking_id', $payment_id)->first();
        if ($existingPayment) {
            // Payment already processed
            throw new \Exception('Payment already processed.');
        }

        DB::beginTransaction();

        try {
            // Create new payment entry
            $payment = new payments();
            $payment->tracking_id =  $payment_id;
            $payment->order_id = $order_id;
            $payment->currency = "INR";
            $payment->amount = 10;

            // Fetch payment details from Razorpay
            $data = $this->verifyPayment($request->razorpay_payment_id);

            if (isset($data['status']) && $data['status'] === 'captured') {

                $payment->bank_ref_no = $data['acquirer_data']['bank_transaction_id'] ?? null;
                $payment->order_status = 'Success';
                $payment->payment_mode = $data['method'];
                $payment->trans_date   = now();
                $payment->save();


                $prePayment = PrePayment::where('order_id', $order_id)->first();

                if (!$prePayment) {
                    throw new \Exception('Payment details not found.');
                }

                $bank_ref_no = $data['acquirer_data']['bank_transaction_id'];
                $this->repository->storeInformation($order_id,$payment_id,$bank_ref_no);

                DB::commit();

                return inertia('Backend/Citizen/Information/Receipt',[
                    'data' => [
                        'customerName' => Auth::user()->name,
                        'orderID' => $order_id,
                        'bankRefNo' => $bank_ref_no,
                        'amount' => 10,
                        'dateTime' => $payment->created_at,
                        'orderStatus' => $payment->order_status,
                        'paymentMode' => $payment->payment_mode,
                        'contact' => $data['contact'] ?? null,
                    ]
                ]);


            } elseif (isset($data['status']) && $data['status'] === 'failed') {
                $payment->bank_ref_no = $data['acquirer_data']['bank_transaction_id'] ?? null;
                $payment->order_status = 'Failure';
                $payment->trans_date   = now();
                $payment->error_code = $data['error_code'];
                $payment->error_description = $data['error_description'];
                $payment->error_source = $data['error_source'];
                $payment->error_reason = $data['error_reason'];
                $payment->save();

                DB::commit();


                return inertia('Backend/Citizen/Information/Receipt',[
                    'data' => [
                        'customerName' => Auth::user()->name,
                        'orderID' => $order_id,
                        'bankRefNo' => $data['acquirer_data']['bank_transaction_id'],
                        'amount' => 10,
                        'dateTime' => $payment->created_at,
                        'orderStatus' => $payment->order_status,
                        'paymentMode' => $payment->payment_mode,
                        'contact' => $data['contact'] ?? null,
                    ]
                ]);

            } else {
                $payment->bank_ref_no = $data['acquirer_data']['bank_transaction_id'] ?? null;
                $payment->order_status = $data['status'];
                $payment->trans_date   = now();
                $payment->error_code = $data['error_code'];
                $payment->error_description = $data['error_description'];
                $payment->error_source = $data['error_source'];
                $payment->error_reason = $data['error_reason'];
                $payment->save();

                DB::commit();

                return view('payment.payment-abort');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }




    }

}
