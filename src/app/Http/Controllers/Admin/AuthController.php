<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ログイン画面
    public function showLogin()
    {
        return view('admin.login');
    }

    // ログイン処理
    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {

            return redirect('/admin/attendance/list');
        }

        return back()->withErrors([
            'login' => 'ログイン情報が登録されていません'
        ]);
    }
}
