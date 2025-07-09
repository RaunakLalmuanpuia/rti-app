<?php

namespace App\Repositories;

use App\Models\Department;
use App\Models\Information;
use App\Models\LocalCouncil;
use App\Models\PrePayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
class InformationRepository
{

    public function getInformation(){

    }
    public function storePreInformation($request, $order_id, $amount){

        $user = Auth::user();

        $prePayment = new PrePayment();


        $prePayment->citizen_name = $user->name;
        $prePayment->citizen_contact = $user->contact;
        $prePayment->citizen_address = $user->address;
        $prePayment->citizen_question = $request->question;

        if($request->input('department') !== null){
            $prePayment->citizen_question_department = $request->department ;
        }

        if($request->input('local_council') !== null){
            $prePayment->citizen_question_local_council= $request->local_council ;
        }

        if ($request['life_or_death'] == "true") {
            $prePayment->life_or_death = true;
        } else {
            $prePayment->life_or_death = false;
        }


        if ($request['attachment'] != null) {
            $data = [];
            foreach ($request['attachment'] as $file) {

                $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief', 'jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];
                $extension = $file->getClientOriginalExtension();

                if (in_array($extension, $imageExtensions)) {
                    $imageResize = Image::make($file);
                    $name = "file" . time() . rand(1000, 9999) . '.' . 'jpeg';
                    // $path = public_path()."/files"."/".$name;
                    $path = storage_path('app/public') . "/files" . "/" . $name;

                    $imageResize->save($path, 50, 'jpg');
                    $data[] = $name;
                } else {
                    // Is not Image
                    $name = "file" . time() . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                    // $file->move(public_path().'/files/', $name);
                    $file->move(storage_path('app/public') . '/files/', $name);

                    $data[] = $name;
                }
            }
            $prePayment->citizen_question_file = implode(",", $data); //TURN THE ARRAY INTO STRING SEPERATE BY COMMA
        }

        $prePayment->order_id = $order_id;
        $prePayment->amount = $amount;

        $prePayment->save();

        return $prePayment;

    }
    public function storeInformation($order_id,$payment_id,$bank_ref_no){

        $prePayment = PrePayment::where('order_id', $order_id)->first();

        $now = Carbon::now();
        $user = Auth::user();

        $information = new Information();

        if(!is_null($prePayment['citizen_question_department'])){
            $selectedDept = Department::select('id')->where('id',$prePayment['citizen_question_department'])->first();
            $myAspio = User::where('department',$selectedDept->id)->where('bio','aspio')->where('status','Accept')->get();
            $mySpio = User::where('department',$selectedDept->id)->where('bio','spio')->where('status','Accept')->get();

            if($myAspio->isEmpty()){
                //There is no ASPIO direct to SPIO
                $information->spio_in = $now;
            }
            else{
                //There is ASPIO
                $information->aspio_in = $now;
                if(!$mySpio->isEmpty()) //new!!
                    $information->spio_in = $now; //new!!
            }
        }
        else
        {
            $selectedLC = LocalCouncil::select('id')->where('id',$prePayment['citizen_question_local_council'])->first();
            $myAspio = User::where('local_council',$selectedLC->id)->where('bio','aspio')->where('status','Accept')->get();
            $mySpio = User::where('local_council',$selectedLC->id)->where('bio','spio')->where('status','Accept')->get();

            if($myAspio->isEmpty()){
                //There is no ASPIO direct to SPIO
                $information->spio_in = $now;
            }
            else{
                //There is ASPIO
                $information->aspio_in = $now;
                if(!$mySpio->isEmpty()) //new!!
                    $information->spio_in = $now; //new!!
            }
        }

        $information->user_id = $user->id;
        $information->citizen_name = $user->name;
        $information->citizen_contact = $user->contact;
        $information->citizen_address = $user->address;
        $information->citizen_question = $prePayment['citizen_question'];
        $information->citizen_question_file = $prePayment['citizen_question_file'];
        $information->life_or_death = $prePayment['life_or_death'];

        if(!is_null($prePayment['citizen_question_department'])){
            $selectedDept = Department::select('id')->where('id',$prePayment['citizen_question_department'])->first();
            $information->citizen_question_department = $selectedDept->id;
        }
        else
        {
            $selectedLC = LocalCouncil::select('id')->where('id',$prePayment['citizen_question_local_council'])->first();
            $information->citizen_question_locall_council = $selectedLC->id;
        }


        if(!is_null($prePayment['citizen_question_department'])){
            //2. IF SAPIO PRESENT SENT NOTIFICATION TO SAPIO ELSE SENT TO SPIO
            $mySpioList = User::where('department',$selectedDept->id)->where('bio','spio')->where('status','Accept')->get();
        }
        else
        {
            //2. IF SAPIO PRESENT SENT NOTIFICATION TO SAPIO ELSE SENT TO SPIO
            $mySpioList = User::where('local_council',$selectedLC->id)->where('bio','spio')->where('status','Accept')->get();
        }

        if((count($mySpioList)==0)){
            return response()->json(['message'=>"SPIO Not Registered",'status'=>404],200);
        }


        $information->order_id = $order_id;
        $information->tracking_id = $payment_id;
        $information->bank_ref_no = $bank_ref_no;


        $information->save();

        //Notify Spio

        return true;

    }

}
