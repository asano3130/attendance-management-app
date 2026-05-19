<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>COACHTECH</title>

    <link rel="stylesheet" href="{{ asset('css/admin/common.css') }}">

    @yield('css')
</head>

<body>

    <header class="admin-header">

        <div class="admin-header__inner">

            <!-- ロゴ -->
            <div class="admin-header__logo">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="COACHTECH">
            </div>

            <!-- メニュー -->
            <nav class="admin-header__nav">

                <a href="/admin/attendance/list">
                    勤怠一覧
                </a>

                <a href="{{ route('admin.staff.list') }}">
                    スタッフ一覧
                </a>

                <a href="/admin/stamp_correction_request/list">
                    申請一覧
                </a>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="logout-button">
                        ログアウト
                    </button>
                </form>

            </nav>

        </div>

    </header>

    <main>
        @yield('content')
    </main>

</body>

</html>