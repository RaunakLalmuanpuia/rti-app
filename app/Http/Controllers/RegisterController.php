<?php

namespace App\Http\Controllers;

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
//        if (\auth()->check()) {
//            return redirect()->to(route('dashboard'));
//        }
        return inertia('Frontend/Auth/Register');
    }
    public function sendOtp(Request $request)
    {
        $data=$this->validate($request, [
            'name' => 'required',
            'email'=>['required',Rule::unique('users','email')],
            'mobile'=>['required','digits:10',Rule::unique('users','mobile')],
            'password'=>'required|confirmed|min:6'
        ]);

        $phoneOtp=env('APP_DEBUG')?1111:rand(1000, 9999);


        Otp::query()->create([
            'recipient' => $data['mobile'],
            'type' => "Register",
            'otp' => $phoneOtp
        ]);


        AppUtil::sendOtp($phoneOtp, $data['mobile']);


        return response()->json(['status'=>true]);
    }

    public function confirmOtp(Request $request)
    {
        $data=$this->validate($request, [
            'mobile_otp'=>'required',
            'name' => 'required',
            'email'=>['required',Rule::unique('users','email')],
            'mobile'=>['required','digits:10',Rule::unique('users','mobile')],
            'password'=>'required|confirmed|min:6'
        ]);
        $mobileOtp=Otp::query()->where('recipient', $data['mobile'])
            ->where('otp',$data['mobile_otp'])
            ->where('used',false)
            ->first();

        if (blank($mobileOtp)) {
            return response()->json(['errors' => [
                'mobile_otp' => 'Invalid Mobile OTP'
            ]],400);
        }

        $temp = array_merge($data,['password'=>Hash::make($data['password'])]);

        $user=DB::transaction(function () use ($mobileOtp, $temp) {
            $user= User::query()->create($temp);
            $user->assignRole('Player');

            $mobileOtp->update(['used'=>true]);

            return $user;
        });

        Auth::login($user);
        return to_route('dashboard');
    }
}
