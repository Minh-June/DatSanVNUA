<?php

namespace App\Http\Requests\Admin\Type;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s]+$/u', // KhÄ‚Â´ng cho phÄ‚Â©p sÃ¡Â»â€˜ & kÄ‚Â½ tÃ¡Â»Â± Ã„â€˜Ã¡ÂºÂ·c biÃ¡Â»â€¡t
                'unique:types,name,' . $this->route('type_id'), // Cho UpdateRequest
            ],
        ];
    }

}
