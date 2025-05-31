<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'rating',
        'comment',
    ];

    /**
     * Barber bilan bog'lanish (bu user modeliga bog'lanadi).
     */
    public function barber()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
