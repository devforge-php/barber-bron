<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Booking;
use App\Models\DayOff;
use App\Models\Service;
use App\Models\WorkSchedule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image',
        'username',
        'name',
        'lastname',
        'phone',
        'text_uz',
        'text_ru',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function service()
    {
   return  $this->hasMany(Service::class);
    }
    public function dayoff()
    {
    return    $this->hasMany(DayOff::class);  
    }
    public function bookings()
{
    return $this->hasMany(Booking::class);
}
public function reviews()
{
    return $this->hasMany(Review::class, 'user_id');
}
public function WorkSchedules()
{
return $this->hasMany(WorkSchedule::class);
}
}
