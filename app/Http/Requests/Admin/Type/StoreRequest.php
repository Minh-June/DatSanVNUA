<?php

namespace App\Http\Requests\Admin\Type;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
                'regex:/^[\p{L}\s]+$/u', // Chỉ cho chữ cái và khoảng trắng
                'unique:types,name', // Kiểm tra trùng tên trong bảng types
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên loại sân !',
            'name.regex' => 'Tên loại sân không được chứa số hoặc ký tự đặc biệt !',
            'name.unique' => 'Tên sân đã tồn tại, vui lòng đặt tên khác !',
        ];
    }
}
