<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    use HasFactory;
    protected $guarded = [];

 public function barber()
{
    return $this->belongsTo(User::class, 'user_id');
}

}
