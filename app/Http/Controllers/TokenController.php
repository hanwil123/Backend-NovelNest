<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class TokenController extends Controller
{
    public function getToken()
    {
        try {
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);

            return response()->json(['token' => $token], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate token'], 500);
        }
    }
}
