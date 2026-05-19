<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateAttendanceRequest extends FormRequest
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
            'clock_in' => 'required',
            'clock_out' => 'required',
            'note' => 'required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $clockIn = strtotime($this->clock_in);
            $clockOut = strtotime($this->clock_out);

            if ($clockIn >= $clockOut) {

                $validator->errors()->add(
                    'clock_in',
                    '出勤時間もしくは退勤時間が不適切な値です'
                );
            }

            foreach ($this->breaks ?? [] as $break) {

                if (
                    !empty($break['start']) &&
                    !empty($break['end'])
                ) {

                    $breakStart = strtotime($break['start']);
                    $breakEnd = strtotime($break['end']);

                    if (
                        $breakStart < $clockIn ||
                        $breakStart > $clockOut
                    ) {

                        $validator->errors()->add(
                            'break',
                            '休憩時間が不適切な値です'
                        );
                    }

                    if ($breakEnd > $clockOut) {

                        $validator->errors()->add(
                            'break',
                            '休憩時間もしくは退勤時間が不適切な値です'
                        );
                    }
                }
            }
        });
    }

    public function messages()
    {
        return [
            'note.required' =>
            '備考を記入してください',
        ];
    }
}
