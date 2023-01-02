<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class SanctumCookieToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = Cookie::get('token');
        if ($token == null) {
            // return redirect()->route('login');
            return response([
                'status' => false,
                'message' => 'unauthorized',
            ], 401);
        }
        $response = $next($request);
        // $response = $response instanceof RedirectResponse ? $response : response($response);
        $response->header('accept', 'application/json');
        return $response->header('Authorization', 'Bearer ' . $token);
    }
}
