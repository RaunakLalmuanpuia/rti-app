<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    use HasFactory;
    // Define the correct table name
    protected $table = 'otps';

    protected $fillable = [
        'contact',
        'otp', 'purpose'
    ];
}
