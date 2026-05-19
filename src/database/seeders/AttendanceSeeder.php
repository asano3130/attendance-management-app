<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        // 前月〜次月
        for ($month = -1; $month <= 1; $month++) {

            $currentMonth =
                Carbon::now()->addMonths($month);

            $days =
                $currentMonth->daysInMonth;

            for ($day = 1; $day <= $days; $day++) {

                $date =
                    $currentMonth
                    ->copy()
                    ->day($day);

                if ($date->isWeekend()) {
                    continue;
                }

                $attendance =
                    Attendance::create([

                        'user_id' => $user->id,

                        'date' =>
                        $date->format('Y-m-d'),

                        'clock_in' => '09:00:00',

                        'clock_out' => '18:00:00',

                        'note' => '通常勤務',

                        'status' => 'done',
                    ]);

                BreakTime::create([

                    'attendance_id' =>
                    $attendance->id,

                    'break_start' => '12:00:00',

                    'break_end' => '13:00:00',
                ]);
            }
        }
    }
}
