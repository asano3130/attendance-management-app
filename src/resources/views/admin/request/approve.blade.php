@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/request-approve.css') }}">
@endsection

@section('content')

<div class="approve">

    <h2 class="approve__title">
        勤怠詳細
    </h2>

    <div class="approve__content">

        <table class="approve-table">

            <tr>
                <th>名前</th>
                <td>
                    {{ $requestData->user->name }}
                </td>
            </tr>

            <tr>
                <th>日付</th>

                <td>
                    {{ \Carbon\Carbon::parse($requestData->attendance->date)->format('Y年n月j日') }}
                </td>
            </tr>

            <tr>
                <th>出勤・退勤</th>

                <td>
                    {{ substr($requestData->requested_clock_in,0,5) }}
                    〜
                    {{ substr($requestData->requested_clock_out,0,5) }}
                </td>
            </tr>

            <tr>
                <th>備考</th>

                <td>
                    {{ $requestData->reason }}
                </td>
            </tr>

        </table>

        {{-- 承認待ち --}}
        @if($requestData->status === 'pending')

        <form
            action="{{ route('admin.request.approve', $requestData->id) }}"
            method="POST">
            @csrf

            <button
                type="submit"
                class="approve-button">
                承認
            </button>
        </form>

        @else

        {{-- 承認済み --}}
        <button
            class="approved-button"
            disabled>
            承認済み
        </button>

        @endif

    </div>

</div>

@endsection