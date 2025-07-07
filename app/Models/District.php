<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $fillable = ['name']; // Add other fillable fields as necessary

    // Define the relationship with LocalCouncil
    public function localCouncils()
    {
        return $this->hasMany(LocalCouncil::class);
    }
}
