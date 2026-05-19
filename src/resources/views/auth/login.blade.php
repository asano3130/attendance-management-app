@extends('layouts.guest')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
<div class="login">
    <h2 class="login__title">ログイン</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form__group">
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form__group">
            <label>パスワード</label>
            <input type="password" name="password">
            @error('password')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button class="login__btn">ログインする</button>
    </form>

    <p class="register__link">
        <a href="{{ route('register') }}">会員登録はこちら</a>
    </p>
</div>
@endsection