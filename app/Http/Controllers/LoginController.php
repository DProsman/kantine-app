<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Demo-login: je kiest een gebruiker uit de lijst. Niet voor productie!
 */
class LoginController extends Controller
{
    public function show()
    {
        return view('login', [
            'gebruikers' => User::orderBy('rol')->orderBy('name')->get(),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate(['user_id' => ['required', 'exists:users,id']]);

        Auth::loginUsingId($request->integer('user_id'));
        $request->session()->regenerate();

        return redirect()->route('bestellen');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
