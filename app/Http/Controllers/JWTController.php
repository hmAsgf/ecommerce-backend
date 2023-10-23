<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTController extends Controller
{
    public static function createToken($payload = []) {
        try {
            $payload['iat'] = Carbon::now()->unix();
            $payload['exp'] = Carbon::now()->addMinutes(env('JWT_EXPIRED_IN_MINUTES'))->unix();
            $token = JWT::encode($payload, env('JWT_KEY'), env('JWT_ALGORITHM'));
            return $token;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function decodeToken() {
        try {
            $token = request()->bearerToken();
            $decodedToken = JWT::decode($token, new Key(env('JWT_KEY'), env('JWT_ALGORITHM')));
            return $decodedToken;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function isValidToken() {
        try {
            $token = request()->bearerToken();
            JWT::decode($token, new Key(env('JWT_KEY'), env('JWT_ALGORITHM')));
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
