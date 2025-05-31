<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function loginUsers($phone, $password)
    {
        $user = User::where('phone', $phone)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return [
                'status' => 'error',
                'message' => 'Telefon yoki parol noto‘g‘ri'
            ];
        }


        $token = $user->createToken('user_token')->plainTextToken;

        return [
            'status' => 'success',
            'token' => $token,
            'role' => $user->role,
        ];
    }
}
