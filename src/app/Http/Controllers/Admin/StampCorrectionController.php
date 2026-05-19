<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceCorrectionRequest;
use App\Models\Attendance;

class StampCorrectionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status ?? 'pending';

        $requests = AttendanceCorrectionRequest::with([
            'user',
            'attendance'
        ])
            ->where('status', $status)
            ->get();

        return view('admin.request.list', compact(
            'requests',
            'status'
        ));
    }

    // 詳細
    public function show($id)
    {
        $requestData =
            AttendanceCorrectionRequest::with([
                'user',
                'attendance',
                'attendance.breakTimes'
            ])->findOrFail($id);

        return view(
            'admin.request.approve',
            compact('requestData')
        );
    }

    // 承認
    public function approve($id)
    {
        $request =
            AttendanceCorrectionRequest::findOrFail($id);

        $attendance = $request->attendance;

        // 勤怠更新
        $attendance->update([

            'clock_in' =>
            $request->requested_clock_in,

            'clock_out' =>
            $request->requested_clock_out,

            'note' =>
            $request->note,
        ]);

        // 承認済みに変更
        $request->update([
            'status' => 'approved',
        ]);

        return redirect()
            ->back()
            ->with('message', '承認しました');
    }
}
