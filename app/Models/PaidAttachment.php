<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaidAttachment extends Model
{
    use HasFactory;
    protected $fillable = ['information_id','order_id','amount','attachment_name','payment_status','param1','param2',
    'param3','param4','param5'];


    // Define the relationship with the Information model
    public function information()
    {
        return $this->belongsTo(Information::class, 'information_id');
    }

}
