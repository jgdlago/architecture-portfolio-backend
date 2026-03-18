<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAdminAccountPasswordRequest;
use App\Http\Requests\UpdateAdminAccountRequest;
use App\Support\PublicApiCache;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    public function update(UpdateAdminAccountRequest $request): Response
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $validated = $request->validated();
        $nextEmail = strtolower($validated['email']);

        if (strcasecmp($nextEmail, (string) $user->email) !== 0 && ! Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Senha atual inválida.'],
            ]);
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $nextEmail,
            'cau' => $validated['cau'] ?? null,
        ]);

        PublicApiCache::bust();

        return response([
            'user' => $user->fresh(),
        ]);
    }

    public function updatePassword(UpdateAdminAccountPasswordRequest $request): Response
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $validated = $request->validated();

        if (! Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Senha atual inválida.'],
            ]);
        }

        $user->update([
            'password' => $validated['password'],
        ]);

        return response([
            'message' => 'Senha atualizada com sucesso.',
        ]);
    }
}
