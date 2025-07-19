<?php

namespace App\Repositories;

use App\Models\Department;
use App\Models\Information;
use App\Models\LocalCouncil;
use App\Models\PaidAttachment;
use App\Models\PrePayment;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

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

                $name = "file" . time() . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                // $file->move(public_path().'/files/', $name);
                $file->move(storage_path('app/public') . '/files/', $name);

                $data[] = $name;

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
    public function storeFirstAppeal($request, $information)
    {
        $now = Carbon::now();

        $information->first_appeal_daa_in = $now;
        $information->first_appeal_citizen_question = $request['appeal_reason'];

        if($request['attachment']!=null){
            $data =[];
            foreach($request['attachment'] as $file){
                $name = "1app".time().rand(1000,9999).'.'.$file->getClientOriginalExtension();
                //    $file->move(public_path().'/files/', $name);
                $file->move(storage_path('app/public').'/files/', $name);

                $data[] = $name;
            }
            $information->first_appeal_citizen_question_file = implode(",",$data);//TURN THE ARRAY INTO STRING SEPERATE BY COMMA
        }
        $information->save();

        return $information;
    }
    public function storeSecondAppeal($request, $information){

        $now = Carbon::now();

        $information->second_appeal_cic_in = $now;

        $information->second_appeal_citizen_question = $request['appeal_reason'];
        if($request['attachment']!=null){
            $data =[];
            foreach($request['attachment'] as $file){
                $name = "2app".time().rand(1000,9999).'.'.$file->getClientOriginalExtension();
                //    $file->move(public_path().'/files/', $name);
                $file->move(storage_path('app/public').'/files/', $name);

                $data[] = $name;
            }
            $information->second_appeal_citizen_question_file = implode(",",$data);//TURN THE ARRAY INTO STRING SEPERATE BY COMMA
        }
        $information->update();
        return $information;
    }
    public function storeComment($comment, $information){

        $now = Carbon::now();
        $user = Auth::user();

        $information->aspio_answer = $comment;
        $information->aspio_id = $user->id;

        $information->aspio_name = $user->name;
        $information->aspio_contact = $user->contact;
        $information->aspio_email = $user->email;

        $information->spio_in = $now;
        $information->update();

        return $information;
    }
    public function storeAnswer(Information $information, array $data)
    {
        $now = Carbon::now();
        $user = Auth::user();

        if (!empty($data['attachment'])) {
            $fileData = [];

            foreach ($data['attachment'] as $file) {
                $name = 'spio' . time() . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/files/'), $name);
                $fileData[] = $name;
            }

            // Store filenames in comma-separated format
            $information->spio_answer_file = implode(',', $fileData);

            // Store PaidAttachment
            $paidAttachment = new PaidAttachment();
            $paidAttachment->information_id = $information->id;
            $paidAttachment->user_id = $information->user_id;

            $paidAttachment->amount = $data['attachment_price'] ?? 0;
            $paidAttachment->attachment_name = implode(',', $fileData);

            if (isset($data['is_free']) && $data['is_free'] === false) {
                $paidAttachment->payment_status = 'Unpaid'; // Paid but not yet received
            } else {
                $paidAttachment->payment_status = 'Free';
            }

            $paidAttachment->save();
        }

        $information->spio_answer = $data['answer'];
        $information->spio_out = $now;
        $information->spio_id = $user->id;

        //add on25May,2023, Officer ho ID ang a lo store chu name leh phone dah belh.
        $information->spio_name = $user->name;
        $information->spio_contact = $user->contact;
        $information->spio_email = $user->email;
        //end

        $information->information_status = 1; //answer by SPIO

        //https://docs.google.com/document/d/1K-5aqsG9HYl7dTd8p7h7PqIavSrq57JbqKbov-Q4CjI/edit
        //THIS ABOVE CODE IS DEFINE IN THE LINK
        $information->update();
        return $information;
    }

    public function transferInformation(Information $information, array $data)
    {
        $now = Carbon::now();
        $user = Auth::user();

        dd($data);

    }

}
