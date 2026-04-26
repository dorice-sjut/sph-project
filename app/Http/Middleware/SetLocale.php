<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check session first
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        // Then check user preference if logged in
        elseif (auth()->check() && auth()->user()->preferred_language) {
            App::setLocale(auth()->user()->preferred_language);
            Session::put('locale', auth()->user()->preferred_language);
        }
        // Default to English
        else {
            App::setLocale('en');
            Session::put('locale', 'en');
        }

        return $next($request);
    }
}
