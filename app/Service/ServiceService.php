<?php 

// App/Service/ServiceService.php

namespace App\Service;

use App\Models\Service;
use Illuminate\Support\Facades\Cache;

class ServiceService
{
public function getPaginated()
{
    $page = request('page', 1);

    return Cache::remember("services_page_{$page}", 3600, function () {
        return Service::with('barber')->latest()->paginate(10);
    });
}



    public function store(array $data): Service
    {
        $service = Service::create($data);
        Cache::forget('services_all');
        return $service->load('barber');
    }

  public function update(Service $service, array $data): Service
{
    $service->fill($data); // Ma'lumotlarni to'ldirish

    if ($service->isDirty()) { // Faqat o'zgarish bo'lganda saqlash
        $service->save();
    }

    Cache::forget('services_all');

    $service->load('barber'); // barber aloqasini yuklash (null qaytarmaydi)

    return $service;
}

    public function delete(Service $service): void
    {
        $service->delete();
        Cache::forget('services_all');
    }
}