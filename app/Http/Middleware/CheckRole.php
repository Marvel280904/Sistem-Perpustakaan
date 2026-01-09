<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Check role
        $user = auth()->user();
        
        if ($role === 'admin' && !$user->isAdmin()) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        
        if ($role === 'member' && !$user->isMember()) {
            abort(403, 'Unauthorized access. Member only.');
        }
        
        return $next($request);
    }
}