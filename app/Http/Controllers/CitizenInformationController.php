<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Information;
use App\Models\LocalCouncil;
use App\Models\PaidAttachment;
use App\Models\PrePaymentAttachment;
use App\Repositories\InformationRepository;

use App\Traits\CanPay;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CitizenInformationController extends Controller
{

    use CanPay;
    private InformationRepository $informationRepository;

    public function __construct(InformationRepository $informationRepository)
    {
        $this->repository = $informationRepository;
    }
    public function create(){

        return Inertia::render('Backend/Citizen/Information/Create');
    }
    public function searchDepartment(Request $request)
    {
        $search = $request->input('search', '');

        $departments = Department::select('id', 'name')
            ->where('name', 'LIKE', "%{$search}%")
            ->whereNotIn('name', [
                'AMC(LC)', 'LMC(LC)', 'Local Administration Department (Secretariat)'
            ])
            ->whereHas('users', function ($query) {
                $query->where('bio', 'spio')->where('status', 'Accept');
            })
            ->limit(20)
            ->get();

        return response()->json($departments);
    }
    public function getLocalCouncil(Request $request){

        $councils = LocalCouncil::where('district', $request->district)
            ->whereHas('users', function ($query) {
                $query->where('bio', 'spio')->where('status', 'Accept');
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($councils);

    }
    public function store(Request $request){

        $request->validate([
            'department' => [ 'nullable'],
            'local_council' => ['nullable'],
            'question' => ['required', 'string'],
            'life_or_death' => ['required'],
            'attachment' => ['nullable', 'array'],
            'attachment.*' => ['file', 'mimes:pdf,jpg,jpeg,png']
        ]);

        $razorpayOrder=$this->initiatePayment();

        $order_id = $razorpayOrder['id'];
        $amount  = $razorpayOrder['amount'];

        $preInformation=$this->repository->storePreInformation($request,$order_id,$amount);

        abort_if(blank($preInformation),500,'Something went wrong');

        return response()->json([
            'order_id' => $razorpayOrder['id'],
            'amount' => $razorpayOrder['amount'],
            'currency' => $razorpayOrder['currency'],
            'receipt' => $razorpayOrder['receipt'],
            'key' => env('RAZORPAY_KEY_ID')
        ]);

    }
    public function show(Information $info){

        $info->load(['department', 'local_council','paidAttachments']);

        return Inertia::render('Backend/Citizen/Information/Show',[
            'info' => $info
        ]);
    }
    public function payAttachment(PaidAttachment $attachment){

        $amount = $attachment->amount;
        $razorpayOrder=$this->initiatePaymentAttachment($amount);

        $prePaymentAttachment = new PrePaymentAttachment();
        $prePaymentAttachment->information_id = $attachment->information_id;
        $prePaymentAttachment->order_id = $razorpayOrder['id'];

        $prePaymentAttachment->save();

        return response()->json([
            'order_id' => $razorpayOrder['id'],
            'amount' => $razorpayOrder['amount'],
            'currency' => $razorpayOrder['currency'],
            'receipt' => $razorpayOrder['receipt'],
            'key' => env('RAZORPAY_KEY_ID')
        ]);
    }
    public function firstAppeal(Request $request, Information $information){

        $validated = $request->validate([
            'appeal_reason' => ['required', 'string', 'min:20', 'max:1000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);
        dd($validated['appeal_reason']);

    }
    public function secondAppeal(Request $request){

    }


}
