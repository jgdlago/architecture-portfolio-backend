<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used for passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\ValidationRule|\Illuminate\Validation\Rules\Password|string>
     */
    protected function passwordRules(): array
    {
        return ['string', Password::min(8)];
    }
}
