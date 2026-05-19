<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Requests\AdminUpdateAttendanceRequest;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('admin.attendance.list');
    }

    public function list(Request $request)
    {
        // 日付取得
        $date = $request->date ?? now()->format('Y-m-d');

        $currentDate = Carbon::parse($date);

        // 前日・翌日
        $prevDate = $currentDate->copy()->subDay()->format('Y-m-d');
        $nextDate = $currentDate->copy()->addDay()->format('Y-m-d');

        // 表示用
        $displayDate = $currentDate->format('Y年n月j日');

        // 勤怠取得
        $attendances = Attendance::with('user', 'breakTimes')
            ->whereDate('date', $date)
            ->get();

        return view('admin.attendance.list', compact(
            'attendances',
            'displayDate',
            'prevDate',
            'nextDate',
            'date'
        ));
    }

    public function detail($id)
    {
        $attendance = Attendance::findOrFail($id);

        return view(
            'admin.attendance.detail',
            compact('attendance')
        );
    }

    public function update(
        AdminUpdateAttendanceRequest $request,
        $id
    ) {

        $attendance = Attendance::findOrFail($id);

        $date = $attendance->date;

        $attendance->update([
            'clock_in' =>
            $date . ' ' . $request->clock_in,

            'clock_out' =>
            $date . ' ' . $request->clock_out,
        ]);

        foreach ($request->breaks ?? [] as $breakId => $break) {

            $breakTime =
                \App\Models\BreakTime::find($breakId);

            if ($breakTime) {

                $breakTime->update([
                    'break_start' =>
                    $break['start']
                        ? $date . ' ' . $break['start']
                        : null,

                    'break_end' =>
                    $break['end']
                        ? $date . ' ' . $break['end']
                        : null,
                ]);
            }
        }

        return redirect()
            ->back()
            ->with('message', '修正しました');
    }

    public function staff(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $month = $request->month ?? now()->format('Y-m');

        $current = \Carbon\Carbon::parse($month . '-01');

        $prevMonth = $current->copy()->subMonth()->format('Y-m');
        $nextMonth = $current->copy()->addMonth()->format('Y-m');

        $currentMonth = $current->format('Y/m');

        $attendances = Attendance::where('user_id', $id)
            ->whereYear('date', $current->year)
            ->whereMonth('date', $current->month)
            ->get();

        return view(
            'admin.attendance.staff',
            compact(
                'user',
                'attendances',
                'currentMonth',
                'prevMonth',
                'nextMonth'
            )
        );
    }

    public function csv(Request $request, $id)
    {
        $month = $request->month ?? now()->format('Y-m');

        $current = Carbon::parse($month . '-01');

        $attendances = Attendance::with('user')
            ->where('user_id', $id)
            ->whereYear('date', $current->year)
            ->whereMonth('date', $current->month)
            ->get();

        $csvData = [];

        $csvData[] = [
            '日付',
            '出勤',
            '退勤'
        ];

        foreach ($attendances as $attendance) {

            $csvData[] = [
                $attendance->date,
                optional($attendance->clock_in)
                    ? Carbon::parse($attendance->clock_in)->format('H:i')
                    : '',

                optional($attendance->clock_out)
                    ? Carbon::parse($attendance->clock_out)->format('H:i')
                    : '',
            ];
        }

        $filename = 'attendance.csv';

        $handle = fopen('php://temp', 'r+');

        foreach ($csvData as $row) {

            fputcsv($handle, $row);
        }

        rewind($handle);

        $csv = stream_get_contents($handle);

        fclose($handle);

        return response($csv)
            ->header(
                'Content-Type',
                'text/csv'
            )
            ->header(
                'Content-Disposition',
                'attachment; filename="' . $filename . '"'
            );
    }
}
