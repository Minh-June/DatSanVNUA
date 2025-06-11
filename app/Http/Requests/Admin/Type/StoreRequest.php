<?php

namespace App\Http\Requests\Admin\Type;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cáº§n xĂ¡c Ä‘á»‹nh quyá»n ngÆ°á»i dĂ¹ng táº¡i Ä‘Ă¢y náº¿u cáº§n
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s]+$/u', // KhĂ´ng cho phĂ©p sá»‘ & kĂ½ tá»± Ä‘áº·c biá»‡t
                'unique:types,name,' . $this->route('type_id'), // Cho UpdateRequest
            ],
        ];
    }
}
