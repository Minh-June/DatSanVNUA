<?php
namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date' => 'required|date|after_or_equal:today',
            'time_from' => ['required', 'regex:/^([01]\d|2[0-3]):([0-5]\d)$/'],
            'time_to' => ['required', 'regex:/^([01]\d|2[0-3]):([0-5]\d)$/'],
        ];
    }

    public function messages()
    {
        return [
            'date.required' => 'Vui lòng chọn ngày.',
            'date.after_or_equal' => 'Ngày phải từ hôm nay trở đi.',
            'time_from.required' => 'Vui lòng nhập giờ bắt đầu.',
            'time_from.regex' => 'Giờ bắt đầu không đúng định dạng (HH:mm).',
            'time_to.required' => 'Vui lòng nhập giờ kết thúc.',
            'time_to.regex' => 'Giờ kết thúc không đúng định dạng (HH:mm).',
        ];
    }
}
