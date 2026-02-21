<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntryRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'type_id' => ['required', 'integer', 'exists:entry_types,id'],
            'topics' => ['nullable', 'array'],
            'topics.*' => ['integer', 'exists:topics,id'],
        ];
    }
}
