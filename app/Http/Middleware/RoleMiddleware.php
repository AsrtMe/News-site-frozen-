<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Проверяем, что пользователь залогинен
        if (! $request->user()) {
            return redirect('/login');
        }

        // Проверяем, совпадает ли роль
        if ($request->user()->role !== $role) {
            abort(403, 'У вас нет доступа к этой странице.');
        }

        return $next($request);
    }
}