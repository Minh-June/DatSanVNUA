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
            'notes'          => 'nullable|string',
            'selected_times' => 'required|array|min:1',      // Đổi thành array
            'selected_times.*' => 'string',                  // Mỗi phần tử trong mảng là string
            'date'           => 'required|date|after_or_equal:today',
            'total_price'    => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'        => 'ID người dùng là bắt buộc.',
            'yard_id.required'        => 'ID sân là bắt buộc.',
            'selected_times.required' => 'Vui lòng chọn ít nhất một khung giờ.',
            'date.required'           => 'Ngày là bắt buộc.',
            'date.after_or_equal'     => 'Ngày đặt phải là hôm nay hoặc sau.',
            'total_price.required'    => 'Giá tổng là bắt buộc.',
            'total_price.numeric'     => 'Giá tổng phải là số.',
        ];
    }
}
