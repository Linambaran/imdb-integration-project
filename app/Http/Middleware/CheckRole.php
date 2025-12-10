<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
        public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        if ($request->user()->role !== $role) {
            // Kalau beda, tolak akses (403 Forbidden)
            abort(403, 'Cannot access this page, You do not have the access.');
        }
        return $next($request);
    }
}