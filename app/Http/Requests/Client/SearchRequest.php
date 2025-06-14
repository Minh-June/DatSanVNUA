<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'type' => 'required|exists:types,type_id',
            'time_from' => 'required|date_format:H:i',
            'time_to' => 'required|date_format:H:i|after:time_from',
        ];
    }

    public function messages(): array
    {
        return [
            'time_from.date_format' => 'Vui lòng nhập đúng định dạng theo mẫu 00:00.',
            'time_to.date_format' => 'Vui lòng nhập đúng định dạng theo mẫu 00:00.',
            'time_to.after' => 'Khung giờ kết thúc phải sau giờ bắt đầu.',
            'date.required' => 'Vui lòng chọn ngày.',
            'type.required' => 'Vui lòng chọn loại sân.',
            'type.exists' => 'Loại sân không hợp lệ.',
        ];
    }
}
