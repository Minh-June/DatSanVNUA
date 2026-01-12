<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class MonthlyRentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // cho phép tất cả người dùng đã login
    }

    public function rules(): array
    {
        return [
            'yard_id'   => 'required|exists:yards,yard_id',
            'from_date' => 'required|date|after_or_equal:today',
            'to_date'   => 'required|date|after_or_equal:from_date',
            'weekdays'  => 'required|array|min:1',
            'weekdays.*'=> 'integer|between:0,6', // 0=Thứ 2 … 6=CN
            'time_from' => 'required|date_format:H:i',
            'time_to'   => [
                'required',
                'date_format:H:i',
                function($attribute, $value, $fail){
                    $from = strtotime($this->input('time_from'));
                    $to   = strtotime($value);
                    if (($to - $from) < 30*60) {
                        $fail('Giờ kết thúc phải cách giờ bắt đầu tối thiểu 30 phút.');
                    }
                }
            ],
        ];
    }

    protected function prepareForValidation()
    {
        $weekdays = $this->input('weekdays');

        // Nếu là string dạng "0,2,4", chuyển thành array [0,2,4]
        if (is_string($weekdays)) {
            $this->merge([
                'weekdays' => array_map('intval', explode(',', $weekdays))
            ]);
        }

        // Nếu gửi JSON, decode luôn
        if (is_string($weekdays) && str_starts_with($weekdays, '[')) {
            $this->merge([
                'weekdays' => json_decode($weekdays, true)
            ]);
        }
    }

    public function withValidator($validator)
    {
        $validator->after(function($validator){
            $fromDate = $this->input('from_date');
            $toDate   = $this->input('to_date');
            $weekdays = $this->input('weekdays', []);

            if ($fromDate && $toDate && !empty($weekdays)) {
                $from = strtotime($fromDate);
                $to   = strtotime($toDate);

                foreach($weekdays as $day) {
                    $valid = false;
                    for($d = $from; $d <= $to; $d += 86400) { // duyệt từng ngày
                        $w = date('w', $d);         // 0=CN … 6=Thứ 6
                        $dayIndex = $w == 0 ? 6 : $w - 1; // chuẩn 0=Thứ 2 … 6=CN
                        if ($dayIndex == $day) {
                            $valid = true;
                            break;
                        }
                    }
                    if (!$valid) {
                        $validator->errors()->add(
                            'weekdays',
                            'Ngày trong tuần chọn phải nằm trong khoảng từ ngày bắt đầu đến ngày kết thúc.'
                        );
                        break;
                    }
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'yard_id.required'        => 'ID sân là bắt buộc.',
            'yard_id.exists'          => 'Sân không tồn tại.',
            'from_date.required'      => 'Ngày bắt đầu là bắt buộc.',
            'from_date.date'          => 'Ngày bắt đầu không hợp lệ.',
            'from_date.after_or_equal'=> 'Ngày bắt đầu phải từ hôm nay trở đi.',
            'to_date.required'        => 'Ngày kết thúc là bắt buộc.',
            'to_date.date'            => 'Ngày kết thúc không hợp lệ.',
            'to_date.after_or_equal'  => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'weekdays.required'       => 'Vui lòng chọn ít nhất 1 ngày trong tuần.',
            'weekdays.array'          => 'Ngày trong tuần phải là mảng hợp lệ.',
            'weekdays.*.integer'      => 'Ngày trong tuần phải là số nguyên từ 0 đến 6.',
            'weekdays.*.between'      => 'Ngày trong tuần phải nằm từ Thứ 2 đến Chủ nhật.',
            'time_from.required'      => 'Giờ bắt đầu là bắt buộc.',
            'time_from.date_format'   => 'Giờ bắt đầu không đúng định dạng H:i.',
            'time_to.required'        => 'Giờ kết thúc là bắt buộc.',
            'time_to.date_format'     => 'Giờ kết thúc không đúng định dạng H:i.',
        ];
    }
}
