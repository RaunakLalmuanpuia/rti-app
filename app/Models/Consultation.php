<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at','answered_date','received_date'];
   
    public function information(){
        return $this->belongsTo(Information::class,'information_id');
        
    }

    public function department(){
        return $this->belongsTo(Department::class,'departments_id');
    }
}
