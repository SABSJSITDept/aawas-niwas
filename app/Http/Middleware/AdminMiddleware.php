<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login'); // ✅ If not logged in, redirect to login
        }

        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Access Denied');
        }

        return $next($request);
    }
}
