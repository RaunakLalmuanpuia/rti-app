<?php

namespace App\Util;

use Illuminate\Support\Facades\Http;

class SmsManager{

    const APPLICATION_RECEIVED = '1407165390471580882';

    const APPLICATION_UPDATED = '1407165606041900553';
    const APPLICATION_TRANSFERRED = '1407165390484228069';

    const FIRST_APPEAL_RECEIVED = '1407165390488786993';

    const SECOND_APPEAL_RECEIVED = '1407165390488786993';

    const COMPLAIN_RECEIVED = '1407165390488786993';

    private string $msg;
    private string $mobile;
    private $msg_type;

    public function send()
    {
        $response=Http::withHeaders([
            'Authorization' => "Bearer " . env('SMS_TOKEN'),
        ])->get("https://sms.msegs.in/api/send-sms",[
            'template_id' => $this->msg_type,
            'message' => $this->msg,
            'recipient'=>$this->mobile
        ]);
        info("SMS response ".$response->body());
    }
    public function setData($msg_type,string $mobile,string $var=null)
    {
        switch ($msg_type) {
            case self::APPLICATION_RECEIVED:
                $this->msg = "RTI Application received. Please visit the portal to response.";
                break;
            case self::APPLICATION_UPDATED:
                $this->msg = "Your application has been updated. Please visit the portal for more info.";
                break;
            case self::APPLICATION_TRANSFERRED:
                $this->msg = "Your RTI application is transferred to ". $var. ".";
                break;
            case self::FIRST_APPEAL_RECEIVED:
                $this->msg = "First Appeal has been filed. Please visit rti.mizoram.gov.in to response.";
                break;
            case self::SECOND_APPEAL_RECEIVED:
                $this->msg = "Second Appeal has been filed. Please visit rti.mizoram.gov.in to response.";
                break;
            case self::COMPLAIN_RECEIVED:
                $this->msg = "Complaint has been filed. Please visit rti.mizoram.gov.in to response.";
                break;

        }
        $this->mobile = $mobile;
        $this->msg_type = $msg_type;
    }

    public function sendMessage($msg_type,string $mobile,string $var)
    {
        throw_if(blank($msg_type), new \Exception("No match found"));

        $appUrl = env('APP_URL');

        switch ($msg_type) {
            case self::APPLICATION_RECEIVED:
                $msg = "RTI Application received. Please visit the portal to response.";
                break;
            case self::APPLICATION_UPDATED:
                $msg = "Your application has been updated. Please visit the portal for more info.";
                break;
            case self::APPLICATION_TRANSFERRED:
                $msg = "Your RTI application is transferred to ". $var. ".";
                break;
            case self::FIRST_APPEAL_RECEIVED:
                $this->msg = "First Appeal has been filed. Please visit rti.mizoram.gov.in to response.";
                break;
            case self::SECOND_APPEAL_RECEIVED:
                $this->msg = "Second Appeal has been filed. Please visit rti.mizoram.gov.in to response.";
                break;
            case self::COMPLAIN_RECEIVED:
                $this->msg = "Complaint has been filed. Please visit rti.mizoram.gov.in to response.";
                break;

        }
        $response=Http::withHeaders([
            'Authorization' => "Bearer " . env('SMS_TOKEN'),
        ])->get("https://sms.msegs.in/api/send-sms",[
            'template_id' => $msg_type,
            'message' => $msg,
            'recipient'=>$mobile
        ]);
        info("SMS response $msg_type ".$response->body());
    }


}
