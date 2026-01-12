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
                'regex:/^[\p{L}\s]+$/u',
                'unique:types,name,' . $this->route('type_id') . ',type_id', // Trừ bản ghi hiện tại
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên loại sân!',
            'name.regex' => 'Tên loại sân chỉ được chứa chữ cái và khoảng trắng!',
            'name.unique' => 'Tên loại sân đã tồn tại, vui lòng đặt tên khác!',
        ];
    }
}
