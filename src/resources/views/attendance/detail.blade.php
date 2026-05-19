@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-detail.css') }}">
@endsection

@section('content')
<div class="detail">

    <h2 class="detail__title">勤怠詳細</h2>

    <form action="/attendance/detail/{{ $attendance->id }}" method="POST">
        @csrf

        <div class="card">

            <!-- 名前 -->
            <div class="row">
                <div class="label">名前</div>
                <div class="value">{{ Auth::user()->name }}</div>
            </div>

            <!-- 日付 -->
            <div class="row">
                <div class="label">日付</div>
                <div class="value">
                    {{ \Carbon\Carbon::parse($attendance->date)->format('Y年') }}
                    {{ \Carbon\Carbon::parse($attendance->date)->format('n月j日') }}
                </div>
            </div>

            <!-- 出勤・退勤 -->
            <div class="row">
                <div class="label">出勤・退勤</div>
                <div class="value">
                    <input type="time" name="clock_in" value="{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i')  }}">
                    〜
                    <input type="time" name="clock_out" value="{{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}">
                </div>
            </div>

            @error('clock_in')
            <p class="error">{{ $message }}</p>
            @enderror

            <!-- 休憩 -->
            @foreach($attendance->breakTimes as $index => $break)

            <div class="row">
                <div class="label">
                    休憩{{ $index + 1 }}
                </div>

                <div class="value">
                    <input
                        type="time"
                        name="breaks[{{ $index }}][start]"
                        value="{{ \Carbon\Carbon::parse($break->break_start)->format('H:i') }}">

                    〜

                    <input
                        type="time"
                        name="breaks[{{ $index }}][end]"
                        value="{{ \Carbon\Carbon::parse($break->break_end)->format('H:i') }}">
                </div>
            </div>

            @endforeach

            <!-- 追加用 -->
            <div class="row">
                <div class="label">
                    休憩{{ $attendance->breakTimes->count() + 1 }}
                </div>

                <div class="value">
                    <input type="time" name="breaks_new[start]">

                    〜

                    <input type="time" name="breaks_new[end]">
                </div>
            </div>
            <!-- 備考 -->
            <div class="row">
                <div class="label">備考</div>
                <div class="value">
                    <textarea name="note">{{ old('note', $attendance->note) }}</textarea>
                </div>
            </div>

            @error('note')
            <p class="error">{{ $message }}</p>
            @enderror

        </div>

        <!-- 承認待ち -->
        @if($attendance->status === '承認待ち')
        <p class="error">承認待ちのため修正はできません。</p>
        @else
        <div class="btn-area">
            <button type="submit">修正</button>
        </div>
        @endif

    </form>
</div>
@endsection