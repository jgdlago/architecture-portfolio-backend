<?php

namespace App\Http\Requests;

use App\Enums\ProfileHeadlinesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'headline' => ['required', Rule::enum(ProfileHeadlinesEnum::class)],
            'bio' => 'nullable|string',
            'city_id' => 'nullable|exists:cities,id',
            'years_experience' => 'nullable|integer|min:0',
            'avatar_path' => 'nullable|string|max:255',
        ];
    }
}
