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
        // $id = $request->route('id'); // Try to get 'id' from the route, it may be null
        // $user = auth()->user();

        // // Check if the user is a student
        // if ($user && $user->role == 'student') {
        //     // If there's an 'id' in the route, check if it matches the authenticated student's id
        //     if ($id && $user->id != $id) {
        //         abort(401); // Unauthorized if id mismatch
        //     }

        return $next($request); // Proceed with the request
        // }

        // // If not a student, return unauthorized
        // abort(401);
    }
}
