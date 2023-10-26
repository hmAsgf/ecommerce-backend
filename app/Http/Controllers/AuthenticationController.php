<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $validator = Users::validate($request->all());

        if($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => false,
            ], 400);
        }

        $user = Users::getUserByEmail($request->email);

        if($user && Hash::check($request->password, $user->password)) {
            $payload = [
                'id' => $user->id,
                'role' => Roles::getRoleValue($user->role_id),
                'email' => $user->email
            ];
            $token = JWTController::createToken($payload);

            return response()->json(['token' => $token]);
        }
        else {
            return response()->json([
                'message' => 'Email atau password salah!',
                'status' => false,
            ], 400);
        }
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function loginGoogle()
    {
        try {
            $userGoogle = Socialite::driver('google')->user();
            $user = Users::loginGoogle($userGoogle);

            $payload = [
                'id' => $user->id,
                'role' => Roles::getRoleValue($user->role_id),
                'email' => $user->email
            ];
            $token = JWTController::createToken($payload);

            return response()->json(['token' => $token]);
        }
        catch (\Throwable $th) {
            return response()->json([
                'message' => 'Terjadi kesalahan!',
                'status' => false,
            ], 400);
        }
    }
}
