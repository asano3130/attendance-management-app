<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>

    <header class="header">
        <div class="header__inner">
            <div class="header__logo">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="COACHTECH">
            </div>

            <nav class="header__nav">

                @auth

                <a href="/attendance">勤怠</a>
                <a href="/attendance/list">勤怠一覧</a>
                <a href="/stamp_correction_request/list">申請</a>

                <form action="/logout" method="POST">
                    @csrf

                    <button type="submit" class="logout-button">
                        ログアウト
                    </button>
                </form>

                @endauth
                
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

</body>

</html>