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
<<<<<<< HEAD
            'name' => 'required|string|max:255|unique:types,name,' . $this->route('type_id'),
        ];
    }
=======
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s]+$/u', // Không cho phép số & ký tự đặc biệt
                'unique:types,name,' . $this->route('type_id'), // Cho UpdateRequest
            ],
        ];
    }

>>>>>>> 80d6e7c (Cập nhật giao diện)
}
