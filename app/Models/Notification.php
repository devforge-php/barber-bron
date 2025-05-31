<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];

    public $incrementing = false; // Id raqamli emasligini aytadi
    protected $keyType = 'string'; // Id string ekanligini aytadi

    public function notifiable()
    {
        return $this->morphTo();
    }
}
