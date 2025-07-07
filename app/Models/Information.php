<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Information extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['user_id','citizen_name','citizen_contact','citizen_address','citizen_sex','citizen_question','citizen_question_file','razorpay_payment_id',
    'spio_in','spio_answer','spio_answer_file','spio_transfer_remark','spio_transfer_department','spio_out',
    'first_appeal_daa_in','first_appeal_daa_name','first_appeal_citizen_question','first_appeal_citizen_question_file','first_appeal_daa_answer','first_appeal_daa_answer_file','first_appeal_daa_out',
    'second_appeal_cic_in','second_appeal_cic_name','second_appeal_citizen_question','second_appeal_citizen_question_file','second_appeal_cic_answer','second_appeal_cic_answer_file','second_appeal_cic_out',
    'secondhand_question_previous_department','secondhand_question_previous_department_remark','complaint_read'];

    public function department()
    {
      return $this->belongsTo(Department::class,'citizen_question_department');
    }

    public function consultation()
    {
      return $this->hasMany(Consultation::class);
    }


    public function local_council()
    {
      return $this->belongsTo(LocalCouncil::class,'citizen_question_locall_council');
    }

    public function paidAttachments()
    {
        return $this->hasOne(PaidAttachment::class, 'information_id');
    }



}


