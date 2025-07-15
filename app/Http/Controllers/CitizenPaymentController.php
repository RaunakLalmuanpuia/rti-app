<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Models\PaidAttachment;
use App\Models\payments;
use App\Models\PaytmPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CitizenPaymentController extends Controller
{
    //
    public function index(Request $request)
    {
        $perPageWeb = $request->input('perPageWeb', 10);
        $perPageMobile = $request->input('perPageMobile', 10);
        $userId = Auth::id();

        // Get all relevant order IDs from Information and PaidAttachment
        $orderIdsFromInformation = Information::where('user_id', $userId)->pluck('order_id')->toArray();
        $orderIdsFromAttachments = PaidAttachment::where('user_id', $userId)->pluck('order_id')->toArray();

        // Merge and remove duplicates
        $mergedOrderIds = array_unique(array_merge($orderIdsFromInformation, $orderIdsFromAttachments));

        // Fetch payments only if there are any order IDs
        $payments = !empty($mergedOrderIds)
            ? Payments::whereIn('order_id', $mergedOrderIds)->paginate($perPageWeb, ['*'], 'webPage')
            : collect(); // returns an empty collection if no IDs

        // Fetch Paytm payments (only using orderIds from Information, as in original logic)
        $paytmPayments = !empty($orderIdsFromInformation)
            ? PaytmPayment::whereIn('orderId', $orderIdsFromInformation)->paginate($perPageMobile, ['*'], 'mobilePage')
            : collect();

        return Inertia::render('Backend/Citizen/Payment/Index', [
            'payments' => $payments,
            'paytmPayments' => $paytmPayments,
            'perPageWeb' => $perPageWeb,
            'perPageMobile' => $perPageMobile,
        ]);
    }
}
