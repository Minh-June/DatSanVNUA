<?php

namespace App\Http\Requests\Admin\Type;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cần xác định quyền người dùng tại đây nếu cần
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:types,name',
        ];
    }
}
