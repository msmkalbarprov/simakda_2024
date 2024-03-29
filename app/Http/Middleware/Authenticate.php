<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Cookie;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        Cookie::queue(Cookie::forget('simakda_2023_session'));
        Cookie::queue(Cookie::forget('laravel_session'));
        Cookie::queue(Cookie::forget('home_base_session'));
        Cookie::queue(Cookie::forget('api_2024_session'));

        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
