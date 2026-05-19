@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff-attendance.css') }}">
@endsection

@section('content')

<div class="staff-attendance">

    <h2 class="page-title">
        {{ $user->name }}さんの勤怠
    </h2>

    <!-- 月移動 -->
    <div class="month-nav">

        <a href="?month={{ $prevMonth }}">
            ← 前月
        </a>

        <div class="month-center">
            {{ $currentMonth }}
        </div>

        <a href="?month={{ $nextMonth }}">
            翌月 →
        </a>

    </div>

    <!-- テーブル -->
    <table class="attendance-table">

        <tr>
            <th>日付</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>休憩</th>
            <th>合計</th>
            <th>詳細</th>
        </tr>

        @foreach($attendances as $attendance)

        <tr>

            <td>
                {{ $attendance->date }}
            </td>

            <td>
                {{ $attendance->clock_in }}
            </td>

            <td>
                {{ $attendance->clock_out }}
            </td>

            <td>
                <a href="{{ route('admin.attendance.detail', $attendance->id) }}">
                    詳細
                </a>
            </td>

        </tr>

        @endforeach

    </table>

    <!-- CSV -->
    <div class="csv-area">

        <a
            href="{{ route('admin.attendance.csv', $user->id) }}?month={{ request('month') }}"
            class="csv-btn">

            CSV出力

        </a>

    </div>

</div>

@endsection