<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UsersGoogle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cookie;

class OauthControllers extends Controller
{
    public function redirectOauth()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackOauth()
    {
        $googleUser = Socialite::driver('google')->user();
        $registeredUser = UsersGoogle::where('google_id', $googleUser->id)->first();

        if (!$registeredUser) {
            $user = UsersGoogle::updateOrCreate([
                'google_id' => $googleUser->id,
            ], [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => Hash::make(Str::random(24)),
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
            ]);
        } else {
            $user = $registeredUser;
        }

        $token = JWTAuth::fromUser($user);

        // Redirect ke frontend dengan token sebagai parameter
        return redirect()->to('/dashboard?token=' . $token);
    }

    public function updateUser(Request $request) {
        $user = auth()->user()->id;
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }

        $usersGoogle = UsersGoogle::find($user);
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users-google,email,' . $user,
            'number_phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
        ]);

        $usersGoogle ->fill($request);
        return response()->json(['message' => 'User berhasil diupdate']);
    }
    public function refresh()
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
            return response()->json(['token' => $token]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Tidak dapat me-refresh token'], 401);
        }
    }
    public function logout()
    {
        try {
            // Invalidasi token JWT
            JWTAuth::invalidate(JWTAuth::getToken());

            // Hapus token dari cookie
            Cookie::queue(Cookie::forget('token'));

            // Redirect ke halaman utama
            return redirect('/')->with('message', 'Berhasil logout');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Gagal logout');
        }
    }
    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json(['user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }
    }
}
