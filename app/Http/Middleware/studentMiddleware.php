<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class studentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('id');
        $batch = $request->route('batch');
        if (auth()->user()->role == 'student' && (auth()->user()->id == $id || auth()->user()->batch_assigned == $batch)) {
            return $next($request);
        } else {
            abort(401);
        }
    }
}
