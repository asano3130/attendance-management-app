<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'clock_in' => ['required'],
            'clock_out' => ['required'],
            'note' => ['required'],

            'breaks.*.start' => ['nullable', 'date_format:H:i'],
            'breaks.*.end'   => ['nullable', 'date_format:H:i'],
        ];
    }

    public function messages()
    {
        return [
            'clock_in.required'  => '出勤時間もしくは退勤時間が不適切な値です',
            'clock_out.required' => '出勤時間もしくは退勤時間が不適切な値です',
            'note.required' => '備考を記入してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $clockIn  = strtotime($this->clock_in);
            $clockOut = strtotime($this->clock_out);

            // ① 出勤 > 退勤
            if ($clockIn && $clockOut && $clockIn >= $clockOut) {
                $validator->errors()->add('clock_in', '出勤時間もしくは退勤時間が不適切な値です');
            }

            // ② 休憩チェック
            if ($this->breaks) {
                foreach ($this->breaks as $index => $break) {

                    $start = isset($break['start']) ? strtotime($break['start']) : null;
                    $end   = isset($break['end']) ? strtotime($break['end']) : null;

                    // 休憩開始が出勤前 or 退勤後
                    if ($start && ($start < $clockIn || $start > $clockOut)) {
                        $validator->errors()->add("breaks.$index.start", '休憩時間が不適切な値です');
                    }

                    // 休憩終了が退勤後
                    if ($end && $end > $clockOut) {
                        $validator->errors()->add("breaks.$index.end", '休憩時間もしくは退勤時間が不適切な値です');
                    }
                }
            }
        });
    }
}
