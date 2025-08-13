<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Redirect based on role
                if ($user->role === 'student') {
                    return redirect()->route('student-dashboard', ['id' => $user->id]);
                } elseif ($user->role === 'teacher') {
                    return redirect()->route('teacher-dashboard', ['id' => $user->id]);
                } elseif ($user->role === 'staff') {
                    return redirect()->route('staff-dashboard', ['id' => $user->id]);
                }

                // Default fallback
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }

}
