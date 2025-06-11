<?php

namespace App\Http\Requests\Admin\Yard;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type_id' => 'required|exists:types,type_id',
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}0-9\s]+$/u',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'TĂªn sĂ¢n khĂ´ng Ä‘Æ°á»£c Ä‘á»ƒ trá»‘ng.',
            'name.regex' => 'TĂªn sĂ¢n khĂ´ng Ä‘Æ°á»£c chá»©a kĂ½ tá»± Ä‘áº·c biá»‡t.',
            'type_id.required' => 'Vui lĂ²ng chá»n thá»ƒ loáº¡i sĂ¢n.',
            'type_id.exists' => 'Thá»ƒ loáº¡i sĂ¢n khĂ´ng há»£p lá»‡.',
        ];
    }
}
