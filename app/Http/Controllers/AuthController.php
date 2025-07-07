<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;


use App\Models\Otp;
use App\Models\User;
use App\Util\AppUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthController extends Controller
{


    public function create(){
        return inertia('Frontend/Auth/Login', []);
    }

    public function store(LoginRequest $request)
    {

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));

    }
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return Inertia::location('/');

    }

    public function forgotPassword(Request $request)
    {
        return inertia('Frontend/Auth/ForgotPassword',[
        ]);
    }
    private function throwInvalidForgotPasswordError(string $str)
    {
        $error = \Illuminate\Validation\ValidationException::withMessages([
            'userId' => [$str],
        ]);
        throw $error;
    }
    public function sendOtp(Request $request)
    {
        $data=$this->validate($request, [
            'userId' => 'required'
        ]);

        $mobileRegex = '/^[0-9]{10}+$/';

        $isPhone = preg_match($mobileRegex, $data['userId']);

        if ($isPhone) {
            $existPhone=User::query()->where('mobile', $data['userId'])->exists();
            if (!$existPhone) {
                $this->throwInvalidForgotPasswordError('User not found with mobile no');
            }
            $phoneOtp=Otp::query()->create([
                'recipient' => $data['userId'],
                'type' => "Password",
                'otp' => env("APP_DEBUG")?1111:rand(0000, 9999),
            ]);

            AppUtil::sendOtp($phoneOtp,  $data['userId']);

            return response()->json([
                'message' => 'OTP sent to ' . $data['userId'],
                'type'=>'Password'
            ]);
        } else{
            throw ValidationException::withMessages([
                'userId' => ['Invalid email/Mobile']
            ]);
        }
    }
    public function verifyOtp(Request $request)
    {
        $data=$this->validate($request, [
            'userId' => 'required',
            'otp'=>['required'],
        ]);

        $mobileRegex = '/^[0-9]{10}+$/';


        $isPhone = preg_match($mobileRegex, $data['userId']);

        $type =  'Password';

        $verified=Otp::query()->where('type',$type )
            ->where('recipient',$data['userId'])
            ->where('otp',$data['otp'])->latest()->first();

        return response()->json(['data' => $verified]);
    }

    public function changePassword(Request $request)
    {
        $data=$this->validate($request, [
            'userId' => 'required',
            'password' => 'required|confirmed',
        ]);

        $user=User::query()->where('mobile', $data['userId'])->first();

        $user->password = Hash::make($data['password']);
        $user->save();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }
}
