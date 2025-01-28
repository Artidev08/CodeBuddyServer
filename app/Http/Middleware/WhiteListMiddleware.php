<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WhiteListMiddleware
{
    // routes that should be excluded from middleware.
    protected $except = [
        'access-denied','/','login','logout','register'
    ];

    // Handle an incoming request.
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $role = $user->roles[0]->name;
            if($role == 'admin') {
                return $next($request);
            }
        }

        if ($this->inExceptArray($request)) {
            return $next($request);
        }

        $ipAddress = $request->ip();
        // Check if the user's IP address exists in the whitelist_ips table
        $isWhitelisted = DB::table('whitelist_ips')->where('ip_address', $ipAddress)->exists();

        // If IP address is whitelisted, allow the request
        if ($isWhitelisted) {
            return $next($request);
        }
        
        // Otherwise, redirect to the access-denied route
        return redirect()->route('access-denied');

    }


    // if the request has a URI that should pass through the middleware.
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
