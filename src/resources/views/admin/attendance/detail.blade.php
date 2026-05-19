@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance-detail.css') }}">
@endsection

@section('content')

<div class="detail-page">

    <h2 class="detail-title">
        勤怠詳細
    </h2>

    <form
        action="/admin/attendance/{{ $attendance->id }}"
        method="POST">

        @csrf
        @method('PUT')

        <table class="detail-table">

            <tr>
                <th>名前</th>
                <td>{{ $attendance->user->name }}</td>
            </tr>

            <tr>
                <th>日付</th>

                <td>
                    {{ \Carbon\Carbon::parse($attendance->date)->format('Y年') }}

                    {{ \Carbon\Carbon::parse($attendance->date)->format('n月j日') }}
                </td>
            </tr>

            <tr>
                <th>出勤・退勤</th>

                <td class="time-row">

                    <input
                        type="time"
                        name="clock_in"
                        value="{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}">

                    <span>〜</span>

                    <input
                        type="time"
                        name="clock_out"
                        value="{{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}">

                </td>
            </tr>

            @foreach($attendance->breakTimes as $break)

            <tr>

                <th>休憩</th>

                <td class="time-row">

                    <input
                        type="time"
                        name="breaks[{{ $break->id }}][start]"
                        value="{{ $break->break_start ? \Carbon\Carbon::parse($break->break_start)->format('H:i') : '' }}">

                    <span>〜</span>

                    <input
                        type="time"
                        name="breaks[{{ $break->id }}][end]"
                        value="{{ $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '' }}">

                </td>

            </tr>

            @endforeach

            <tr>

                <th>備考</th>

                <td>

                    <textarea
                        name="note"></textarea>

                </td>

            </tr>

        </table>

        <div class="button-area">

            <button type="submit">
                修正
            </button>

        </div>

    </form>

</div>

@endsection