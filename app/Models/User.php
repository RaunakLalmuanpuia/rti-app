<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'bio', 'role','contact','address','department'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /*
    |------------------------------------------------------------------------------------
    | Validations
    |------------------------------------------------------------------------------------
    */
    public static function rules($update = false, $id = null)
    {
        $common = [
            'email'    => "required|email|unique:users,email,$id",
            'password' => 'nullable|confirmed',
            'avatar' => 'image',
        ];

        if ($update) {
            return $common;
        }

        return array_merge($common, [
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /*
    |------------------------------------------------------------------------------------
    | Attributes
    |------------------------------------------------------------------------------------
    */
    public function setPasswordAttribute($value)
    {
        // $this->attributes['password'] = bcrypt($value);
        if($value){
        $this->attributes['password'] = \Hash::needsRehash($value) ? \Hash::make($value) : $value;
        }
    }
    
    public function getAvatarAttribute($value)
    {
        if (!$value) {
            return 'https://placehold.it/160x160';
        }
    
        return config('variables.avatar.public').$value;
    }
    public function setAvatarAttribute($photo)
    {
        //$this->attributes['avatar'] = move_file($photo, 'avatar');
    }

    /*
    |------------------------------------------------------------------------------------
    | Boot
    |------------------------------------------------------------------------------------
    */
    public static function boot()
    {
        parent::boot();
        static::updating(function ($user) {
            $original = $user->getOriginal();
            
            if (\Hash::check('', $user->password)) {
                $user->attributes['password'] = $original['password'];
            }
        });
    }

    public function dept()
    {
       return $this->belongsTo(Department::class,'department');
        //return $this->belongsTo(Department::class);
    }

    public function localCouncil ()
    {
       return $this->belongsTo(LocalCouncil::class,'local_council');
        //return $this->belongsTo(Department::class);
    }

    public function consultation()
    {
      return $this->hasMany(Consultation::class);
    }
  
}
