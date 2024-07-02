<?php

namespace App\Http\Controllers\Auth;

use App\Mail\SendPasswordRecovery;
use App\Models\User;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Laravel\Fortify\Contracts\FailedPasswordResetLinkRequestResponse;
use Laravel\Fortify\Contracts\SuccessfulPasswordResetLinkRequestResponse;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController as BaseController;

class PasswordResetLinkController extends BaseController
{
    public function store(Request $request): Responsable
    {
        $request->validate([Fortify::email() => 'required|email']);

        $user = User::where('email', $request->only(Fortify::email()))->first();

        if ($user) {
            $token = app(PasswordBroker::class)->createToken($user);
            Mail::to($user->email)->send(new SendPasswordRecovery($user, $token));

            Log::info("E-mail de verificação enviado " . $user->email);

            $status = Password::RESET_LINK_SENT;
        } else {
            $status = Password::INVALID_USER;
        }

        return $status == Password::RESET_LINK_SENT
            ? app(SuccessfulPasswordResetLinkRequestResponse::class, ['status' => $status])
            : app(FailedPasswordResetLinkRequestResponse::class, ['status' => $status]);
    }
}
