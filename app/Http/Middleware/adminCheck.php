<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class adminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->path() == 'app/admin_login')  return $next($request);
        $user = Auth::user();
        if (!Auth::check() || $user->userType == 'User') return response()->json([
            'msg'=>'u cannot access this route mf...',
        ],403);
//            return redirect('/login');

        return $next($request);

    }
}
