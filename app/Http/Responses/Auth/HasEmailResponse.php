<?php

namespace App\Http\Responses\Auth;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class HasEmailResponse implements Responsable
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
                    'message' => 'Seu e-mail já foi verificado.'
                ],
                200
            )
            : redirect()->intended(Fortify::redirects('email-verification'));
    }
}
