<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Otp;
use App\Models\User;
use App\Util\AppUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function create(Request $request)
    {
        if (\auth()->check()) {
            return redirect()->to(route('dashboard'));
        }
        return inertia('Frontend/Auth/Register');
    }
    public function sendOtp(Request $request)
    {

        $request->validate([
            'mobile'=>['required','digits:10',Rule::unique('users','contact')],
        ]);

        $phoneOtp=env('APP_DEBUG')?1111:rand(1000, 9999);

        OTP::query()->create([
            'contact' => $request['mobile'],
            'purpose' => "Register",
            'otp' => $phoneOtp
        ]);


//        AppUtil::sendOtp($phoneOtp, $data['mobile']);


        return response()->json(['status'=>true]);
    }

    public function confirmOtp(Request $request)
    {

        $data=$this->validate($request, [
            'mobile_otp'=>'required',
            'mobile'=>['required','digits:10',Rule::unique('users','contact')],
        ]);

        $mobileOtp= OTP::query()->where('contact', $data['mobile'])
            ->where('otp',$data['mobile_otp'])
            ->where('purpose','Register')
            ->first();

        if (!$mobileOtp || $mobileOtp->created_at->addMinutes(5)->lt(now())) {
            return response()->json([
                'errors' => [
                    'mobile_otp' => 'Invalid Mobile OTP'
                ]
            ], 400);
        }


        return response()->json(['status'=>true]);
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

    public function store(Request $request){
//        dd($request->all());

        $data=$this->validate($request, [
            'name' => 'required',
            'email'=>['required',Rule::unique('users','email')],
            'contact'=>['required','digits:10',Rule::unique('users','contact')],
            'password'=>'required|confirmed|min:6',
            'role'=>'required',
            'department'=>'required',
        ]);


    }
}
