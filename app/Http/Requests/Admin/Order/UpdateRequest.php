<?php

namespace App\Http\Requests\Admin\Customer;

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
            'san_id' => 'required|exists:yard,san_id',
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'], // Chá»‰ cho chá»¯ cĂ¡i vĂ  khoáº£ng tráº¯ng
            'phone' => ['required', 'regex:/^[0-9]+$/', 'max:15'], // Chá»‰ sá»‘, khĂ´ng kĂ½ tá»± khĂ¡c
            'date' => 'required|date',
            'time' => 'required|string',
            'price' => 'required|numeric',
            'notes' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'Há» vĂ  tĂªn chá»‰ Ä‘Æ°á»£c chá»©a chá»¯ cĂ¡i vĂ  khoáº£ng tráº¯ng.',
            'phone.regex' => 'Sá»‘ Ä‘iá»‡n thoáº¡i chá»‰ Ä‘Æ°á»£c chá»©a chá»¯ sá»‘.',
        ];
    }
}
