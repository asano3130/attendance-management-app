@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/request-list.css') }}">
@endsection

@section('content')

<div class="request-list">

    <h2 class="request-list__title">申請一覧</h2>

    <div class="request-list__tab">
        <a href="?status=pending"
            class="{{ $status === 'pending' ? 'active' : '' }}">
            承認待ち
        </a>

        <a href="?status=approved"
            class="{{ $status === 'approved' ? 'active' : '' }}">
            承認済み
        </a>
    </div>

    <table class="request-table">

        <tr>
            <th>状態</th>
            <th>名前</th>
            <th>対象日時</th>
            <th>申請理由</th>
            <th>申請日時</th>
            <th>詳細</th>
        </tr>

        @foreach ($requests as $request)

        <tr>

            {{-- 状態 --}}
            <td>
                {{ $request->status === 'pending' ? '承認待ち' : '承認済み' }}
            </td>

            {{-- 名前 --}}
            <td>
                {{ $request->user->name }}
            </td>

            {{-- 対象日時 --}}
            <td>
                {{ \Carbon\Carbon::parse($request->attendance->date)->format('Y/m/d') }}
            </td>

            {{-- 申請理由 --}}
            <td>
                {{ $request->reason }}
            </td>

            {{-- 申請日時 --}}
            <td>
                {{ $request->created_at->format('Y/m/d') }}
            </td>

            {{-- 詳細 --}}
            <td>
                <a href="{{ route('admin.request.show', $request->id) }}">
                    詳細
                </a>
            </td>

        </tr>

        @endforeach

    </table>

</div>

@endsection