<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payments extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'tracking_id', 'bank_ref_no','order_status','payment_mode','card_name','currency','amount',
        'billing_name','discount_value','mer_amount','trans_date','payment_status','error_code','error_description','error_source','error_reason'
    ];

}
