<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    public function handle(Request $request, Closure $next)
    {
        $allowedOrigins = [
            'http://26.176.39.199',
            'http://26.176.39.199:*',
            'http://26.237.194.233',
            'http://26.237.194.233:*',
            'http://localhost',
            'http://localhost:*',
            'http://127.0.0.1',
            'http://127.0.0.1:*',
            // Позволить запросы с любого localhost порта (для фронтенда друга)
            'http://192.168.:*',
            'http://10.:*',
            'http://172.:*',
        ];

        $origin = $request->header('Origin');
        $allowOrigin = $this->isOriginAllowed($origin, $allowedOrigins) ? $origin : 'http://26.176.39.199';

        $headers = [
            'Access-Control-Allow-Origin' => $allowOrigin,
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, X-Requested-With, Authorization',
            'Access-Control-Allow-Credentials' => 'false',
        ];

        if ($request->getMethod() === 'OPTIONS') {
            return response()->json('OK', 200, $headers);
        }

        $response = $next($request);

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }

    private function isOriginAllowed(?string $origin, array $allowedOrigins): bool
    {
        if (!$origin) {
            return false;
        }

        foreach ($allowedOrigins as $allowed) {
            if ($allowed === $origin) {
                return true;
            }
            if (strpos($allowed, ':*') !== false) {
                $base = str_replace(':*', '', $allowed);
                if (strpos($origin, $base) === 0) {
                    return true;
                }
            }
        }

        return false;
    }
}
