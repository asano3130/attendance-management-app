@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance-list.css') }}">
@endsection

@section('content')

<div class="attendance-list">

    <h2 class="page-title">
        {{ $displayDate }}の勤怠
    </h2>

    <!-- 日付移動 -->
    <div class="date-nav">

        <a href="/admin/attendance/list?date={{ $prevDate }}" class="date-link">
            ← 前日
        </a>

        <div class="date-center">
            {{ $date }}
        </div>

        <a href="/admin/attendance/list?date={{ $nextDate }}" class="date-link">
            翌日 →
        </a>

    </div>

    <!-- 一覧 -->
    <table class="attendance-table">

        <tr>
            <th>名前</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>休憩</th>
            <th>合計</th>
            <th>詳細</th>
        </tr>

        @foreach($attendances as $attendance)

        @php

        $breakMinutes = 0;

        foreach ($attendance->breakTimes as $break) {

        if ($break->break_start && $break->break_end) {

        $start = \Carbon\Carbon::parse($break->break_start);
        $end = \Carbon\Carbon::parse($break->break_end);

        $breakMinutes += $start->diffInMinutes($end);
        }
        }

        $workMinutes = 0;

        if ($attendance->clock_in && $attendance->clock_out) {

        $workMinutes =
        \Carbon\Carbon::parse($attendance->clock_in)
        ->diffInMinutes(
        \Carbon\Carbon::parse($attendance->clock_out)
        ) - $breakMinutes;
        }

        @endphp

        <tr>

            <td>{{ $attendance->user->name }}</td>

            <td>
                {{ $attendance->clock_in
                    ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i')
                    : '' }}
            </td>

            <td>
                {{ $attendance->clock_out
                    ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i')
                    : '' }}
            </td>

            <td>
                {{ floor($breakMinutes / 60) }}:{{ str_pad($breakMinutes % 60, 2, '0', STR_PAD_LEFT) }}
            </td>

            <td>
                {{ floor($workMinutes / 60) }}:
                {{ str_pad($workMinutes % 60, 2, '0', STR_PAD_LEFT) }}
            </td>

            <td>
                <a href="{{ route('admin.attendance.detail', $attendance->id) }}">
                    詳細
                </a>
            </td>

        </tr>

        @endforeach

    </table>

</div>

@endsection