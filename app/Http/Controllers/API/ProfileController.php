<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Показать профиль пользователя
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    // Обновить профиль пользователя
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }

    // Удалить аккаунт пользователя
    public function destroy(Request $request)
    {
        $user = $request->user();

        Auth::logout();
        $user->delete();

        return response()->json(['message' => 'Account deleted successfully']);
    }
}
