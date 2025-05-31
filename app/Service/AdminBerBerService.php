<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminBerBerService
{
public function get()
    {
        return User::where('role', 'barber')->get();
    }

    public function store(array $data)
    {
        try {
            if (isset($data['image'])) {
                $data['image'] = $data['image']->store('barbers', 'public');
            }

            $data['password'] = Hash::make($data['password']);
            $data['role'] = 'barber';

            return User::create($data);
        } catch (\Exception $e) {
            throw new \Exception("Barber yaratishda xatolik: " . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            return User::where('role', 'barber')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new \Exception("Barber topilmadi");
        }
    }

    public function update(array $data, $id)
    {
        try {
            $user = User::where('role', 'barber')->findOrFail($id);

            // Agar rasm bo'lsa, eski rasmni o'chirib yangisini yuklash
            if (isset($data['image'])) {
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }
                $data['image'] = $data['image']->store('barbers', 'public');
            }

            // Agar parol bo'sh bo'lmasa, hash qilamiz, aks holda o'zgartirmaymiz
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            return $user;

        } catch (ModelNotFoundException $e) {
            throw new \Exception("Barber topilmadi");
        } catch (\Exception $e) {
            throw new \Exception("Yangilashda xatolik: " . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $user = User::where('role', 'barber')->findOrFail($id);

            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            $user->delete();

            return true;

        } catch (ModelNotFoundException $e) {
            throw new \Exception("Barber topilmadi");
        }
    }
}
