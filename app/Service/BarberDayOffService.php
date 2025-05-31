<?php 


// App\Service\BarberDayOffService.php

namespace App\Service;

use App\Models\DayOff;
use Illuminate\Support\Facades\Cache;

class BarberDayOffService
{
    protected const CACHE_KEY = 'day_off_';

    public function getAll(int $userId)
    {
        return Cache::rememberForever(self::CACHE_KEY . $userId, function () use ($userId) {
            return DayOff::where('user_id', $userId)->get();
        });
    }

    public function getById(int $id, int $userId)
    {
        return DayOff::where('id', $id)->where('user_id', $userId)->firstOrFail();
    }

    public function create(array $data, int $userId)
    {
        $dayOff = DayOff::create([
            'user_id' => $userId,
            'day_off' => $data['day_off'],
        ]);

        $this->clearCache($userId);
        return $dayOff;
    }

    public function update(array $data, int $id, int $userId)
    {
        $dayOff = $this->getById($id, $userId);
        $dayOff->update(['day_off' => $data['day_off']]);

        $this->clearCache($userId);
        return $dayOff;
    }

    public function delete(int $id, int $userId): bool
    {
        $dayOff = $this->getById($id, $userId);
        $dayOff->delete();

        $this->clearCache($userId);
        return true;
    }

    private function clearCache(int $userId): void
    {
        Cache::forget(self::CACHE_KEY . $userId);
    }
}