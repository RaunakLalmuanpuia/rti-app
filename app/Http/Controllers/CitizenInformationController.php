<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Information;
use App\Models\LocalCouncil;
use App\Models\PaidAttachment;
use App\Models\PrePaymentAttachment;
use App\Models\User;
use App\Repositories\InformationRepository;

use App\Traits\CanPay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CitizenInformationController extends Controller
{

    use CanPay;
    private InformationRepository $informationRepository;

    public function __construct(InformationRepository $informationRepository)
    {
        $this->repository = $informationRepository;
    }

    public function index(){
        return Inertia::render('Backend/Citizen/Information/Index');
    }

    public function indexJson(Request $request)
    {

        $user = Auth::user();

        $search = $request->input('filter');
        $perPage = $request->input('rowsPerPage', 15);


        $query = Information::with(['department','local_council'])
            ->where('user_id', $user->id)
            ->whereNull('complain')
            ->whereNull('transfer');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('citizen_name', 'like', "%{$search}%")
                    ->orWhere('citizen_question', 'like', "%{$search}%")
                    ->orWhereHas('department', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('local_council', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $list = $query
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage)
            ->appends($request->all());

        return response()->json([
            'list' => $list
        ]);
    }

    public function complainJson(Request $request)
    {

        $user = Auth::user();

        $search = $request->input('filter');
        $perPage = $request->input('rowsPerPage', 15);


        $query = Information::with('department')
            ->where('user_id', $user->id)
            ->whereNotNull('complain')
            ->whereNull('transfer');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('citizen_name', 'like', "%{$search}%")
                    ->orWhere('citizen_question', 'like', "%{$search}%")
                    ->orWhereHas('department', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $list = $query
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage)
            ->appends($request->all());

        return response()->json([
            'list' => $list
        ]);
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

        if (!$request->department && !$request->local_council) {
            abort(500, 'Either Department or Local Council is required.');
        }

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
            'attachment' => ['nullable'],
            'attachment.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        // Check if DAA is there if department
        if ($information['citizen_question_department'] !== null){
            //TAKE ALL THE DAA WHICH HAVE THE CONCERN DEPARTMENT
            $mydaa = User::where('department','=',$information->citizen_question_department)->where('bio','=','daa')->where('status','Accept')->first();

            if($mydaa==null){
                $multiDaa = DB::table('users')->whereRaw('FIND_IN_SET(?,sex)',[$information->citizen_question_department])->where('bio','=','daa')->where('status','Accept')->first();

                if($multiDaa!=null){
                    $nameDaa=$multiDaa->name;
                    $emailDaa=$multiDaa->email;
                    $contactDaa=$multiDaa->contact;
                }else{
                    //no DAA
                    abort(500,'No DAA found for the concerned department');
                }

            }else{
                $nameDaa=$mydaa->name;
                $emailDaa=$mydaa->email;
                $contactDaa=$mydaa->contact;
            }
        }
        // Check if DAA is there if Local Council
        if($information['citizen_question_locall_council'] !== null){
            $lc = LocalCouncil::where('id','=', $information->citizen_question_locall_council)->first();
            $district = $lc->district;
            if ($district === 'Aizawl') {
                $mydaa = User::where('bio', 'daa')
                    ->where('status', 'Accept')
                    ->whereHas('dept', function ($query) {
                        $query->where('name', 'AMC(LC)');
                    })
                    ->first();
                if($mydaa==null){
                    abort(500,'No DAA found for the concerned Local Council');
                }else{
                    $nameDaa=$mydaa->name;
                    $emailDaa=$mydaa->email;
                    $contactDaa=$mydaa->contact;
                }
            } else {
                $mydaa = User::where('bio', 'daa')
                    ->where('status', 'Accept')
                    ->whereHas('dept', function ($query) {
                        $query->where('name', 'LMC(LC)');
                    })
                    ->first();
                if($mydaa==null){
                    abort(500,'No DAA found for the concerned Local Council');
                }else{
                    $nameDaa=$mydaa->name;
                    $emailDaa=$mydaa->email;
                    $contactDaa=$mydaa->contact;
                }
            }
        }
        // Send SMS to DAA
//        try{
//            $myMessage =  "First Appeal has been filed. Please visit ".$this->websiteLink." to response.";
//            $templateId = "1407165390488786993";
//            $this->thangteaSMS($contactDaa,$myMessage,$templateId);
//            $this->email( $nameDaa,$emailDaa,$information->citizen_question);
//        }catch(Exception $e){
//            return redirect('information')->withError(trans('No DAA present'));
//        }

        // Store First Appeal
        $firstAppeal =$this->repository->storeFirstAppeal($validated,$information);


        return redirect()->back()->with('message','First Appeal Filled');

    }
    public function secondAppeal(Request $request, Information $information){

        $validated = $request->validate([
            'appeal_reason' => ['required', 'string', 'min:10', 'max:1000'],
            'attachment' => ['nullable'],
            'attachment.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);


//        $myCicList = User::where('bio','=','cic')->get();
//
//        $mContactArray = array();
//        $mNameArray = array();
//        $mEmailArray = array();
//
//        if($myCicList != null){
//            foreach($myCicList as $mySpio){
//                $mContactArray[] = $mySpio->contact;
//                $mNameArray[] = $mySpio->name;
//                $mEmailArray[] = $mySpio->email;
//            }
//            $myMessage =  "Second Appeal has been filed. Please visit ".$this->websiteLink." to response.";
//            $templateId = "1407165390488786993";
//            $this->thangteaSMS($mContactArray,$myMessage,$templateId);
//            $this->email($mNameArray,$mEmailArray,$information->second_appeal_citizen_question_file);
//        }
//

        // Store First Appeal
        $secondAppeal =$this->repository->storeSecondAppeal($validated,$information);

        return response()->json([
            'status' => 'success',
            'message' => 'Second Appeal filed successfully.'
        ]);

    }
    public function createComplain(){
        return Inertia::render('Backend/Citizen/Complain/Create');
    }
    public function storeComplain(Request $request){

        $user = Auth::user();
        $now = Carbon::now();

        $validated = $request->validate([
            'complain' => ['required', 'string', 'min:5', 'max:1000'],
            'attachment' => ['nullable','array'],
            'attachment.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $information = new Information();
        $information->user_id = $user->id;

        $information->citizen_name = $user->name;
        $information->citizen_contact = $user->contact;
        $information->citizen_address = $user->address;
        $information->citizen_question = $validated['complain'];

        if($request['attachment']!=null){
            $data =[];
            foreach($request['attachment'] as $file){
                $name = "comp".time().rand(1000,9999).'.'.$file->getClientOriginalExtension();
                // $file->move(public_path().'/files/', $name);
                $file->move(storage_path('app/public').'/files/', $name);

                $data[] = $name;
            }
            $information->citizen_question_file = implode(",",$data);//TURN THE ARRAY INTO STRING SEPERATE BY COMMA
        }

        $information->complain = true;
        $information->second_appeal_cic_in = $now;
        $information->second_appeal_citizen_question =  $validated['complain'];//CIC TA TUR A NIH AVANGIN CIC QUESTION TUR AH KAN DAH NGHAL
        $information->save();

        //Notify CIC Complain filed -sms

        abort_if(blank($information),500,'Something Went Wrong');

        return response()->json([
            'status' => 'success',
            'message' => 'Complain Submitted Successfully.'
        ]);

    }
    public function showComplain(){
    }


}
