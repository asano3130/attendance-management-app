<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceCorrectionRequest;
use Illuminate\Support\Facades\Auth;


class AttendanceCorrectionRequestController extends Controller
{
    public function index()
    {
        $status = request('status', 'pending');

        $requests =
            AttendanceCorrectionRequest::with([
                'attendance',
                'user'
            ])
            ->where('user_id', Auth::id())
            ->where('status', $status)
            ->get();

        return view(
            'attendance.request-list',
            compact(
                'requests',
                'status'
            )
        );
    }
}
