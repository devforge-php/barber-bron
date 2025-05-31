<?php 

// App\Service\BarBerWorkTime.php

namespace App\Service;

use App\Models\WorkSchedule;
use Illuminate\Support\Facades\Cache;

class BarBerWorkTime
{
    protected const CACHE_KEY = 'work_schedule_';

    public function getAll(int $userId)
    {
        return Cache::rememberForever(self::CACHE_KEY . $userId, function () use ($userId) {
            return WorkSchedule::where('user_id', $userId)->get();
        });
    }

    public function getById(int $id, int $userId)
    {
        return WorkSchedule::where('id', $id)->where('user_id', $userId)->firstOrFail();
    }

    public function create(array $data, int $userId)
    {
        $schedule = WorkSchedule::create([
            'user_id' => $userId,
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
        ]);

        $this->clearCache($userId);
        return $schedule;
    }

    public function update(array $data, int $id, int $userId)
    {
        $schedule = $this->getById($id, $userId);
        $schedule->update($data);

        $this->clearCache($userId);
        return $schedule;
    }

    public function delete(int $id, int $userId)
    {
        $schedule = $this->getById($id, $userId);
        $schedule->delete();

        $this->clearCache($userId);
        return true;
    }

    private function clearCache(int $userId): void
    {
        Cache::forget(self::CACHE_KEY . $userId);
    }
}