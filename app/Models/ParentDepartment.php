<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentDepartment extends Model
{
    use HasFactory;

    public function department()
    {
        return $this->belongsTo(Department::class,'parent_id');
    }

    public function departmentChild()
    {
        return $this->belongsTo(Department::class,'department_id');
    }
}

