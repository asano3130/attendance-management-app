@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/login.css') }}">
@endsection

@section('content')

<div class="login">

    <h1 class="login__title">
        管理者ログイン
    </h1>

    <form action="/admin/login" method="POST" class="login-form">

        @csrf

        <div class="form-group">
            <label>メールアドレス</label>

            <input type="email" name="email">

            @error('email')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>パスワード</label>

            <input type="password" name="password">

            @error('password')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        @error('login')
        <p class="error">{{ $message }}</p>
        @enderror

        <button type="submit">
            管理者ログインする
        </button>

    </form>

</div>

@endsection