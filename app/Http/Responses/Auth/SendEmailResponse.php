<?php

namespace App\Http\Responses\Auth;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class SendEmailResponse implements Responsable
{
    /**
     * @param  $request
     * @return JsonResponse|Response|RedirectResponse
     */
    public function toResponse($request): JsonResponse|Response|RedirectResponse
    {
        return $request->wantsJson()
            ? response()->json(
                [
                    'message' => 'E-mail de verificação enviado.',
                ],
                200
            )
            : back()->with('status', Fortify::VERIFICATION_LINK_SENT);
    }
}
