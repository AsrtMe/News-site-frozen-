<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminController extends Controller
{
    // Главная страница админки
    public function index()
    {
        return view('admin.dashboard');
    }

    // Список пользователей
    public function users()
    {
        $users = User::all();

        return view('admin.users', compact('users'));
    }

    // Блокировка пользователя
    public function block(User $user)
    {
        $user->update(['is_active' => false]);

        return back()->with('success', 'Пользователь заблокирован');
    }

    // Разблокировка
    public function unblock(User $user)
    {
        $user->update(['is_active' => true]);

        return back()->with('success', 'Пользователь разблокирован');
    }

    // Удаление пользователя
    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', 'Пользователь удалён');
    }
}
