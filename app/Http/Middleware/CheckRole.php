<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        /** @var User $user */
        $userRole = $user->getRoleName();
        
        if (!$userRole) {
            abort(403, 'Anda tidak memiliki role yang valid untuk mengakses sistem ini.');
        }

        // Check if user's role is in allowed roles
        if (!in_array($userRole, $roles)) {
            $allowedRoles = implode(', ', $roles);
            abort(403, "Halaman ini hanya dapat diakses oleh role: {$allowedRoles}. Role Anda saat ini: {$userRole}.");
        }

        return $next($request);
    }
}
