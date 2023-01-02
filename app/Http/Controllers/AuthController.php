<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $payload = $request->all();

        $request->validate([
            'name' => 'required|min:2',
            'username' => 'required|min:3|max:20|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8'
        ]);

        User::query()->create($payload);

        return redirect()->route('login')->with(['success' => 'Registered']);
    }

    public function registerView()
    {
        return view('auth.register');
    }

    public function loginView()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        $email = $request->input('email');

        $user = User::query()
            ->where('email', $email)
            ->first();

        if ($user == null) {
            return redirect()->back()->withErrors(['error' => 'Wrong credentials!']);
        }

        if (!Auth::attempt($credentials)) {
            return redirect()->back()->withErrors(['error' => 'Wrong credentials!']);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        session()->regenerate();
        session()->put('logged', true);
        session()->put('id', $user->id);
        return redirect()->intended('/')->withCookie('token', $token);
        // return response([
        //     'status' => true,
        //     'message' => '',
        // ])->withCookie('token', $token);
    }

    public function logout(Request $request) {
        Auth::logout();
        Cookie::expire('token');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
