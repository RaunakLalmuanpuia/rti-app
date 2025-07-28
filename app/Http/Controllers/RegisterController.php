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
    public function createCitizen(Request $request)
    {
        if (\auth()->check()) {
            return redirect()->to(route('dashboard'));
        }
        return inertia('Frontend/Auth/Register/Citizen');
    }
    public function createOfficial(Request $request)
    {
        if (\auth()->check()) {
            return redirect()->to(route('dashboard'));
        }
        return inertia('Frontend/Auth/Register/Official');
    }
    public function createLocalCouncil(Request $request)
    {
        if (\auth()->check()) {
            return redirect()->to(route('dashboard'));
        }
        return inertia('Frontend/Auth/Register/official');
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
            ->limit(20)
            ->get();

        return response()->json($departments);
    }

    public function storeCitizen(Request $request){

        $data=$this->validate($request, [
            'name' => 'required',
            'email'=>['required',Rule::unique('users','email')],
            'contact'=>['required','digits:10',Rule::unique('users','contact')],
            'password'=>'required|confirmed|min:6',
            'address'=>'required',
        ]);


        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'contact' =>$data['contact'],
            'address' => $data['address'],
            'role' => 0,
        ]);

        Auth::login($user);

        return to_route('dashboard');

    }

    public function storeOfficial(Request $request){
//        dd($request->all());

        $data=$this->validate($request, [
            'name' => 'required',
            'email'=>['required',Rule::unique('users','email')],
            'contact'=>['required','digits:10',Rule::unique('users','contact')],
            'password'=>'required|confirmed|min:6',
            'role'=>'required',
            'department'=>'required',
        ]);

        if($data['role']=='spio'){
            $mUser = User::where('bio','spio')->where('department',$data['department'])->first();
            if($mUser!=null){
                abort(500, 'SPIO already present in this department');
            }
        }

        $departmentInput = $data['department'];

        // Normalize department input
        $departmentIds = is_array($departmentInput) ? $departmentInput : [$departmentInput];

        if ($data['role'] === 'daa') {
            $conflictingDeptIds = [];

            // Check structured column
            $existingDaas = User::where('bio', 'daa')
                ->whereIn('department', $departmentIds)
                ->pluck('department')
                ->toArray();

            $conflictingDeptIds = array_merge($conflictingDeptIds, $existingDaas);

            // Check CSV department (sex column)
            foreach ($departmentIds as $deptId) {
                $csvDaa = DB::table('users')
                    ->whereRaw('FIND_IN_SET(?, sex)', [$deptId])
                    ->where('bio', 'daa')
                    ->exists();

                if ($csvDaa) {
                    $conflictingDeptIds[] = $deptId;
                }
            }

            // Remove duplicates
            $conflictingDeptIds = array_unique($conflictingDeptIds);

            if (!empty($conflictingDeptIds)) {
                $departmentNames = Department::whereIn('id', $conflictingDeptIds)
                    ->pluck('name')
                    ->toArray();

                $namesList = implode(', ', $departmentNames);
                abort(500, 'DAA already exists in the following department(s): ' . $namesList);
            }
        }

        $user = new User();
        $user->name = $request->name ;


        $user->designation = $request->designation;

        $user->email = $request->email ;
        $user->password =  Hash::make($request->password) ;
        $user->contact = $request->contact ;
        $user->address = $request->address ;


        if (is_array($data['department'])) {
            $user->department = $data['department'][0]; // Always assign the first department
            if (count($request->department) > 1) {
                // Store all departments as CSV in the 'sex' column
                $user->sex = implode(',', $request->department);
            }
        } else {
            // Single department case
            $user->department = $request->department;
        }

        $user->role = 5 ;
        $user->bio = $data['role'];
        $user->save();

        Auth::login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'User Registered Successfully'
        ]);

    }

    public function storeLocalCouncil(Request $request){
        dd($request->all());

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
