<?php

namespace App\Http\Requests\Admin\Yard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
                // Chỉ cho phép chữ, số, khoảng trắng, dấu - ()
                'regex:/^[\p{L}0-9\s\-\(\)]+$/u',
                // Unique theo type_id
                Rule::unique('yards')->where(function ($query) {
                    return $query->where('type_id', $this->type_id);
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'type_id.required' => 'Vui lòng chọn thể loại sân.',
            'type_id.exists' => 'Thể loại sân không hợp lệ.',
            'name.required' => 'Tên sân không được để trống.',
            'name.regex' => 'Tên sân chỉ được chứa chữ cái, số, khoảng trắng và dấu - ().',
            'name.unique' => 'Tên sân đã tồn tại trong loại sân đã chọn. Vui lòng nhập tên khác!',
        ];
    }
}
