@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-list.css') }}">
@endsection

@section('content')
<div class="list">

    <h2 class="list__title">勤怠一覧</h2>

    {{-- 月切り替え --}}
    <div class="month-nav">
        <a href="?month={{ $prevMonth }}">← 前月</a>
        <div class="month">{{ $currentMonth }}</div>
        <a href="?month={{ $nextMonth }}">翌月 →</a>
    </div>

    {{-- テーブル --}}
    <table class="table">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
            <tr>
                <td>
                    {{ \Carbon\Carbon::parse($attendance->date)->format('m/d(D)') }}
                </td>

                <td>
                    {{ $attendance->clock_in }}
                </td>

                <td>
                    {{ $attendance->clock_out }}
                </td>

                <td>
                    {{ $attendance->breakTimes->count() > 0 ? 'あり' : '' }}
                </td>

                <td>
                    {{ $attendance->work_time ?? '' }}
                </td>

                <td>
                    <a href="/attendance/detail/{{ $attendance->id }}">
                        詳細
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection