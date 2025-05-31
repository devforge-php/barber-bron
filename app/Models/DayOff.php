<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayOff extends Model
{
    use HasFactory;
  protected $fillable = ['user_id', 'day_off'];

public function barber()
{
    return $this->belongsTo(User::class, 'user_id');
}
}
