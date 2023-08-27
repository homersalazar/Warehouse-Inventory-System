<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserAccess
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
        $user = $request->user();

        if (!$user) {
            return redirect('/login'); // Redirect if not logged in
        }
        if ($user->status !== 0) {
            return response("Account not activated, Please contact your admin", 403); // Or redirect to an inactive page
        }
        return $next($request);
    }
}
