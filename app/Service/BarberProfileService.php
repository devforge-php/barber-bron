<?php

namespace App\Service;

use \Log;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class BarberProfileService
{
    private $cacheKeyPrefix = 'barber_profile_';

    public function show(int $userId)
    {
        $key = $this->cacheKey($userId);
        return Cache::remember($key, now()->addDay(), function () use ($userId) {
            return User::findOrFail($userId);
        });
    }

    public function update(int $userId, array $data)
    {
        $key = $this->cacheKey($userId);
        $user = User::findOrFail($userId);
    
     
    
        $updated = $user->update($data); // Bu yerda yangilash sodir bo'ladi
    
        if ($updated) {
            Cache::forget($key); // Keshni yangilash
        }
    
        return $updated;
    }

    private function cacheKey(int $userId): string
    {
        return $this->cacheKeyPrefix . $userId;
    }
}