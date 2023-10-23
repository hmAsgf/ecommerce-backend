<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;

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
                'role' => Roles::getRoleValue($user->id),
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
}
