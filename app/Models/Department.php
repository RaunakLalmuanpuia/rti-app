<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name'];
    protected $dates = ['deleted_at'];

//    public function users()
//    {
//      return  $this->hasMany(User::class);
//    }
    public function users()
    {
        return $this->hasMany(User::class, 'department', 'id');
    }
  public function informations()
  {
    return  $this->hasMany(Information::class);
  }

  public function parent()
  {
    return $this->hasOne(ParentDepartment::class);
  }
}
