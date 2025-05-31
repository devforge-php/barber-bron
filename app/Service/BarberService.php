<?php

namespace App\Service;

use App\Models\Service;
use Illuminate\Support\Facades\Cache;

class BarberService
{
    protected $cacheKey = 'services';

    public function getAllByUser(int $userId)
    {
        return Cache::remember("services:user:$userId", 60, fn() =>
            Service::where('user_id', $userId)->get()
        );
    }

    public function getByIdAndUser(string $id, int $userId)
    {
        return Cache::remember("service:$id:user:$userId", 60, fn() =>
            Service::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail()
        );
    }

    public function create(array $data)
    {
        $this->clearCache($data['user_id']);
        return Service::create($data);
    }

    public function update(string $id, array $data, int $userId)
    {
        $service = Service::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $service->update($data);
        $this->clearCache($userId);
        return $service;
    }

    public function delete(string $id, int $userId)
    {
        $service = Service::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $service->delete();
        $this->clearCache($userId);
    }

    private function clearCache(int $userId)
    {
        Cache::forget($this->cacheKey);
        Cache::forget("services:user:$userId");
    }
}
