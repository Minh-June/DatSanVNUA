<?php

namespace App\Http\Requests\Admin\TimeYard;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'price' => 'required|numeric|min:0',
            'time' => [
                'required',
                'regex:/^\d{2}:\d{2}\s*-\s*\d{2}:\d{2}$/'
            ],
        ];
    }
}
