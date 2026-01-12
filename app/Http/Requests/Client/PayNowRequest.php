<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class PayNowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:5120', // 5MB
            'owner_id' => 'required|exists:users,user_id',
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' => 'Vui lòng tải ảnh thanh toán thành công hoặc chọn phương thức khác.',
            'images.*.image' => 'File phải là hình ảnh.',
            'images.*.mimes' => 'Chỉ chấp nhận jpg, jpeg, png.',
        ];
    }
}
