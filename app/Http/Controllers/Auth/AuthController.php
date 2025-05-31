<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Service\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $phone = $request->input('phone');
        $password = $request->input('password');
    
        $users = $this->authService->loginUsers($phone, $password);
    
        return response()->json($users);
    }
    
}
