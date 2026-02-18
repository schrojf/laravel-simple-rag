<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvitationCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['nullable', 'string', 'regex:/^[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{3}$/', 'unique:invitation_codes,code'],
            'description' => ['nullable', 'string', 'max:500'],
            'active' => ['boolean'],
        ];
    }
}
