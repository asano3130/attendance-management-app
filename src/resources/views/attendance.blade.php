@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
<div class="attendance">

    {{-- ステータス --}}
    <div class="status">
        {{ $status ?? '勤務外' }}
    </div>

    {{-- 日付 --}}
    <div class="date">
        {{ now()->format('Y年n月j日（D）') }}
    </div>

    {{-- 時刻 --}}
    <div class="time">
        {{ now()->format('H:i') }}
    </div>

    {{-- ボタン --}}
    <div class="actions">

        {{-- 勤務外 --}}
        @if(($status ?? '勤務外') === '勤務外')
        <form method="POST" action="/attendance/clock-in">
            @csrf
            <button class="btn">出勤</button>
        </form>
        @endif

        {{-- 出勤中 --}}
        @if(($status ?? '') === '出勤中')
        <form method="POST" action="/attendance/break-start">
            @csrf
            <button class="btn gray">休憩入</button>
        </form>

        <form method="POST" action="/attendance/clock-out">
            @csrf
            <button class="btn">退勤</button>
        </form>
        @endif

        {{-- 休憩中 --}}
        @if(($status ?? '') === '休憩中')
        <form method="POST" action="/attendance/break-end">
            @csrf
            <button class="btn">休憩戻</button>
        </form>
        @endif

        {{-- 退勤済 --}}
        @if(($status ?? '') === '退勤済')
        <p class="message">お疲れ様でした。</p>
        @endif

        @if(session('message'))
        <p class="message">{{ session('message') }}</p>
        @endif

    </div>
</div>
@endsection