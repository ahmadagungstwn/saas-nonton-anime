<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Interfaces\AuthRepositoryInterface;

class AuthController extends Controller
{
    protected $authRepository;
    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }
    public function registerForm()
    {
        return view('auth.register');
    }

    public function storeRegister(RegisterRequest $request)
    {
        $data = $request->validated();
        $this->authRepository->register($data);
        return redirect()->route('home')->with('success', 'User registered successfully');
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function storeLogin(LoginRequest $request)
    {
        $data = $request->validated();
        $this->authRepository->login($data);
        return redirect()->route('profile')->with('success', 'User logged in successfully');
    }

    public function logout()
    {
        return $this->authRepository->logout();
    }
}
