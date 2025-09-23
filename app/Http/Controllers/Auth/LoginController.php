<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = DB::table('tb_user')->where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // store minimal session
            $request->session()->put('user_id', $user->id_user);
            $request->session()->put('username', $user->username);
            $request->session()->put('peran', $user->peran);

            return redirect()->intended('/dashboard')->with('success', 'Login berhasil. Selamat datang ' . $user->username);
        }

        return back()->with('error', 'Username atau password salah')->withInput();
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/login')->with('success', 'Anda telah logout');
    }
}
