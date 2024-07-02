<?php

namespace App\Http\Controllers\Auth;

use App\Http\Responses\Auth\HasEmailResponse;
use App\Http\Responses\Auth\SendEmailResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController as BaseController;

class EmailVerificationNotificationController extends BaseController
{
    /**
     * @param  Request $request
     * @return Responsable
     */
    public function store(Request $request): Responsable
    {
        if ($request->user()->hasVerifiedEmail()) {
            return app(HasEmailResponse::class);
        }

        $request->user()->sendEmailVerificationNotification();

        Log::info("E-mail de recuperação de senha enviado " . $request->user()->email);

        return app(SendEmailResponse::class);
    }
}
