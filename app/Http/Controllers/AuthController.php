<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;


class AuthController extends Controller
{
    public function registerReq(Request $request) {
        $request->validate([
            'namalengkap' => 'required|string|max:255',
            'notelp' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'namalengkap' => $request->namalengkap,
            'notelp' => $request->notelp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


    }
}
