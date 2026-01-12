<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class CartPayNowRequest extends FormRequest
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
            'fullname' => 'required|string|max:255',
            'phonenb'  => 'required|string|max:20',
            'email'    => 'required|email|max:255',
            'address'  => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' => 'Vui lòng tải ảnh thanh toán thành công hoặc chọn phương thức khác.',
            'images.array' => 'Ảnh phải được gửi dưới dạng mảng.',
            'images.min' => 'Vui lòng tải ít nhất một ảnh.',
            'images.*.image' => 'File phải là hình ảnh.',
            'images.*.mimes' => 'Chỉ chấp nhận jpg, jpeg, png.',
            'owner_id.required' => 'Có lỗi: cửa hàng không hợp lệ.',
            'owner_id.exists' => 'Cửa hàng không tồn tại.',
            'fullname.required' => 'Họ và tên không được để trống.',
            'phonenb.required' => 'Số điện thoại không được để trống.',
            'email.required' => 'Email không được để trống.',
            'address.required' => 'Địa chỉ không được để trống.',
        ];
    }
}
