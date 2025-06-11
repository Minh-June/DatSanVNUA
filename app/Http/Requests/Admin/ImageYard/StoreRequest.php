<?php

namespace App\Http\Requests\Admin\ImageYard;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'yard_id' => 'required|exists:yards,yard_id', // sửa lại nếu trước đó ghi sai
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'yard_id.required' => 'Vui lòng chọn sân thể thao.',
            'yard_id.exists' => 'Sân thể thao không hợp lệ.',
            'image.required' => 'Hình ảnh sân không được để trống.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
        ];
    }


}
