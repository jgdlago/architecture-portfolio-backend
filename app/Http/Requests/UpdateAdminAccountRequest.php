<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->user();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'cau' => ['nullable', 'string', 'max:15'],
            'current_password' => [
                Rule::requiredIf(function () use ($user): bool {
                    if (! $user) {
                        return false;
                    }

                    return strcasecmp((string) $this->input('email'), (string) $user->email) !== 0;
                }),
                'string',
            ],
        ];
    }
}
