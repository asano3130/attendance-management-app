<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceCorrectionRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\BreakTime;
use App\Http\Requests\UpdateAttendanceRequest;
use Carbon\Carbon;


class AttendanceController extends Controller
{
    // 画面表示
    public function index()
    {
        $user = Auth::user();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', today())
            ->first();

        // ステータス判定
        if (!$attendance) {
            $status = '勤務外';
        } elseif ($attendance->clock_out) {
            $status = '退勤済';
        } elseif ($attendance->BreakTimes()->whereNull('break_end')->exists()) {
            $status = '休憩中';
        } else {
            $status = '出勤中';
        }

        return view('attendance', compact('status'));
    }

    // 出勤
    public function clockIn()
    {
        $user = Auth::user();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', today())
            ->first();

        if (!$attendance) {
            Attendance::create([
                'user_id' => $user->id,
                'date' => today(),
                'clock_in' => now(),
                'status' => '出勤中',
            ]);
        }

        return redirect('/attendance');
    }

    // 休憩入
    public function breakStart()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->first();

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => now(),
        ]);

        return redirect('/attendance');
    }

    // 休憩戻
    public function breakEnd()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->first();

        $break = BreakTime::where('attendance_id', $attendance->id)
            ->whereNull('break_end')
            ->latest()
            ->first();

        if ($break) {
            $break->update([
                'break_end' => now(),
            ]);
        }

        return redirect('/attendance');
    }

    // 退勤
    public function clockOut()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->first();

        if ($attendance && !$attendance->clock_out) {
            $attendance->update([
                'clock_out' => now(),
                'status' => '退勤済',
            ]);
        }

        return redirect('/attendance')->with('message', 'お疲れ様でした。');
    }

    public function list(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');

        $current = \Carbon\Carbon::parse($month . '-01');

        $prevMonth = $current->copy()->subMonth()->format('Y-m');
        $nextMonth = $current->copy()->addMonth()->format('Y-m');

        $currentMonth = $current->format('Y/m');

        $attendances = Attendance::with('breakTimes')
            ->where('user_id', Auth::id())
            ->whereYear('date', $current->year)
            ->whereMonth('date', $current->month)
            ->get();

        return view('attendance.list', compact(
            'attendances',
            'currentMonth',
            'prevMonth',
            'nextMonth'
        ));
    }

    public function detail($id)
    {
        $attendance = Attendance::with('BreakTimes')->findOrFail($id);

        return view('attendance.detail', compact('attendance'));
    }

    public function update(
        UpdateAttendanceRequest $request,
        $id
    ) {

        $attendance = Attendance::findOrFail($id);

        $date = $attendance->date;

        // 修正申請作成
        AttendanceCorrectionRequest::create([

            'user_id' => Auth::id(),

            'attendance_id' => $attendance->id,

            'requested_clock_in' =>
            $date . ' ' . $request->clock_in,

            'requested_clock_out' =>
            $date . ' ' . $request->clock_out,

            'note' => $request->note,

            'reason' => $request->note,

            'status' => 'pending',
        ]);

        return redirect()
            ->back()
            ->with(
                'message',
                '修正申請を送信しました'
            );
    }

    public function show($id)
    {
        $attendance = Attendance::with('user', 'breaks')->findOrFail($id);

        return view('attendance.detail', compact('attendance'));
    }
}
