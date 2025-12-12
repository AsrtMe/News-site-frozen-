<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    // Регистрация через email (API)
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        /** @var \App\Models\User $user */
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(), // сразу считаем email подтверждённым
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    }

    // Вход через email
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Неверный email или пароль',
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    // Выход (удаляем текущий токен)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    // Получить текущего пользователя
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    // --- Вход/Регистрация через Google ---
    public function googleLogin(Request $request)
    {
        $request->validate([
            'id_token' => 'required',
        ]);

        // Проверяем токен у Google
        $googleResponse = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $request->id_token,
        ]);

        if ($googleResponse->failed()) {
            return response()->json(['error' => 'Неверный Google токен'], 401);
        }

        $googleData = $googleResponse->json();
        $email = $googleData['email'] ?? null;

        if (! $email) {
            return response()->json(['error' => 'Google аккаунт не содержит email'], 400);
        }

        /** @var \App\Models\User $user */
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $googleData['name'] ?? 'No Name',
                'password' => Hash::make(str()->random(12)),
                'email_verified_at' => now(), // email считается подтверждённым
            ]
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }
}
