<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    public function login(array $data)
    {
        return Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ]);
    }

    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);

        Auth::login($user);

        return $user;
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with('success', 'You have logged out.');
    }

    public function updateProfilePicture($user, $file)
    {

        $path = $file->store('profile-picture', 'public');

        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->update([
            'profile_picture' => $path
        ]);

        return $path;
    }
}
