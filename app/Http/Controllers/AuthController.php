<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // View Limitless
    }
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $roleName = $user->roles->pluck('name')->first(); // lấy tên role đầu tiên
            /*
            switch ($roleName) {
                case 'admin':
                    return redirect()->route('dashboard');
                case 'manager':
                    return redirect()->route('dashboard');
                case 'staff':
                    return redirect()->route('dashboard');
                default:
                    return redirect()->route('products.index');
            } 
            */
            return redirect()->route('dashboard');
        }

        return back()
            ->withErrors(['email' => 'Email hoặc mật khẩu không đúng'])
            ->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
