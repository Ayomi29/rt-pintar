<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id'];
    protected $hidden = ['password', 'remember_token'];

    public function roles()
    {
        return $this->hasOne(Role::class);
    }
    public function admin()
    {
        return $this->hasOne(Role::class)->where('role_name', 'admin');
    }
    public function pengurus()
    {
        return $this->hasOne(Role::class)->where('role_name', 'pengurus');
    }
    public function bendahara()
    {
        return $this->hasOne(Role::class)->where('role_name', 'bendagara');
    }

    public function otp_code()
    {
        return $this->hasOne(OtpCode::class);
    }

    public function family_member()
    {
        return $this->hasOne(FamilyMember::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
