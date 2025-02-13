<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class CustomThrottle
{
    public function handle(Request $request, Closure $next)
    {
        // Daftar cache yang akan diperiksa
        $cacheKeys = ['item_key', 'unit_key', 'type_key', 'user_key'];

        // Cek apakah salah satu cache tersedia
        foreach ($cacheKeys as $cacheKey) {
            if (Cache::has($cacheKey)) {
                return $next($request); // Jika ada cache, langsung lanjutkan request tanpa throttle
            }
        }

        // Terapkan throttle jika cache tidak tersedia
        $key = 'throttle:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 6)) {
            return response()->json(['message' => 'Too many requests'], 429);
        }

        RateLimiter::hit($key, 60); // Hitungan berlaku selama 60 detik

        return $next($request);
    }
}
