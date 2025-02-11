<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponseTraitError;
use App\Traits\ApiResponseTraitSuccess;
use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JWTMiddleware
{

    use ApiResponseTraitSuccess;
    use ApiResponseTraitError;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {

            if(!$request->hasHeader('Authorization')) {
                return $this->sendApiError('Token tidak ditemukan!', null);
            }

            // Mengambil dan memverifikasi token yang dikirimkan
            JWTAuth::parseToken()->authenticate();

        } catch (JWTException $e) {
            // Menangani kesalahan JWT: token tidak ditemukan atau kadaluarsa
            if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->sendApiError('Token telah kadaluarsa!', null);
            } elseif ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->sendApiError('Token tidak valid!', null);
            } elseif ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException) {
                return $this->sendApiError('Token tidak ditemukan!', null);
            }
        }
        
        return $next($request);
    }
}
