@extends('layouts.guest')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="register">
    <h2 class="register__title">会員登録</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form__group">
            <label>名前</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

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

        <div class="form__group">
            <label>パスワード確認</label>
            <input type="password" name="password_confirmation">
        </div>

        <button class="register__btn">登録する</button>
    </form>

    <p class="login__link">
        <a href="{{ route('login') }}">ログインはこちら</a>
    </p>
</div>
@endsection