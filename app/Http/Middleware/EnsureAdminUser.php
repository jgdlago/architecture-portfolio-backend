<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminUser
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $adminEmail = config('app.admin_email');

        if (! is_string($adminEmail) || $adminEmail === '') {
            abort(500, 'ADMIN_EMAIL is not configured.');
        }

        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        abort_unless($user && strcasecmp($user->email, $adminEmail) === 0, 403);

        return $next($request);
    }
}
