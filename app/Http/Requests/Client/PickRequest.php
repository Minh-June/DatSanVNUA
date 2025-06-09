<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class PickRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'        => 'required',
            'yard_id'        => 'required',
            'name'           => 'required|string|max:255',
            'phone'          => 'required|regex:/^[0-9]{10}$/',
            'notes'          => 'nullable|string',
            'selected_times' => 'required|array|min:1',      // Đổi thành array
            'selected_times.*' => 'string',                   // Mỗi phần tử trong mảng là string
            'date'           => 'required|date|after_or_equal:today',
            'total_price'    => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'        => 'ID người dùng là bắt buộc.',
            'yard_id.required'        => 'ID sân là bắt buộc.',
            'name.required'           => 'Tên là bắt buộc.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.regex' => 'Số điện thoại chỉ được chứa số và phải có đúng 10 chữ số.',
            'selected_times.required' => 'Vui lòng chọn ít nhất một khung giờ.',
            'date.required'           => 'Ngày là bắt buộc.',
            'date.after_or_equal'     => 'Ngày đặt phải là hôm nay hoặc sau.',
            'total_price.required'    => 'Giá tổng là bắt buộc.',
            'total_price.numeric'     => 'Giá tổng phải là số.',
        ];
    }
}
