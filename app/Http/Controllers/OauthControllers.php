<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UsersGoogle;
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
