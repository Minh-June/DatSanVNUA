<?php

namespace App\Http\Requests\Admin\Yard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $yardId = $this->route('yard_id'); // Lấy đúng theo route param

        return [
            'type_id' => 'required|exists:types,type_id',
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}0-9\s\-\(\)]+$/u',
                Rule::unique('yards')->where(function ($query) {
                    return $query->where('type_id', $this->input('type_id'));
                })->ignore($yardId, 'yard_id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên sân không được để trống.',
            'name.regex' => 'Tên sân không được chứa ký tự đặc biệt.',
            'name.unique' => 'Tên sân này đã tồn tại trong cùng thể loại. Vui long nhập tên sân khác !',
            'type_id.required' => 'Vui lòng chọn thể loại sân.',
            'type_id.exists' => 'Thể loại sân không hợp lệ.',
        ];
    }
}
