<?php

namespace App\Http\Controllers\Barber\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\BarberProfileService;
use App\Http\Resources\BarberResource;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(BarberProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function show(Request $request)
    {
        $user = $request->user();
        return new BarberResource($this->profileService->show($user->id));
    }

    public function update(Request $request)
    {
        $user = $request->user();
    
        // Debug: kiruvchi ma'lumotlarni ko'rish
    
    
        $request->validate([
            'name' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'text_uz' => 'nullable|string',  // Bu yerda text qo'shildi
            'text_ru' => 'nullable|string',  // Bu yerda text qo'shildi
        ]);
    
        $data = $request->except(['password', '_method']);
    
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('barbers', 'public');
        }
    
   
    
        $updated = $this->profileService->update($user->id, $data);
    
        if ($updated) {
            return response()->json(['message' => 'Profile updated successfully']);
        }
    
        return response()->json(['error' => 'Failed to update profile'], 400);
    }
}