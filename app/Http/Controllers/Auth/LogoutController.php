<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Responses\LogoutResponse as FortifyLogoutResponse;

class LogoutController extends AuthenticatedSessionController
{
    /**
     * The guard implementation.
     *
     * @var StatefulGuard
     */
    protected $guard;

    /**
     * Create a new controller instance.
     *
     * @param StatefulGuard $guard
     * @return void
     */
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * @param  Request $request
     * @return LogoutResponse
     */
    public function destroy(Request $request): LogoutResponse
    {
        if ($request->wantsJson()) {
            $request->user()->tokens()->delete(); // revoke all tokens
            return app(FortifyLogoutResponse::class);
        }

        $this->guard->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return app(FortifyLogoutResponse::class);
    }
}
