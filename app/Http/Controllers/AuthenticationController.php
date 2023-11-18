<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\UsersProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $validator = Users::loginValidate($request->all());

        if($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => false,
            ], 400);
        }

        $user = Users::getUserByEmail($request->email);

        if(!$user) {
            return response()->json([
                'message' => 'Email tidak terdaftar!',
                'status' => false,
            ], 400);
        }

        $userProfile = UsersProfile::getUserProfileByUserId($user->id);

        if($user && Hash::check($request->password, $user->password)) {
            $payload = [
                'id' => $user->id,
                'role' => Roles::getRoleValue($user->role_id),
                'email' => $user->email,
                'name' => $userProfile->name,
            ];
            $token = JWTController::createToken($payload);

            return response()->json([
                'token' => $token,
                'status' => true,
            ]);
        }
        else {
            return response()->json([
                'message' => 'Email atau password salah!',
                'status' => false,
            ], 400);
        }
    }

    public function register(Request $request)
    {
        $request['age'] = Carbon::parse($request->birth_date)->age;

        if(!$request['role_id']) {
            $request['role_id'] = '2';
        }

        $userValidator = Users::registerValidate($request->all());
        $userProfileValidator = UsersProfile::registerValidate($request->all());

        if($userValidator->fails()) {
            return response()->json([
                'message' => $userValidator->errors()->first(),
                'status' => false,
            ], 400);
        }

        if($userProfileValidator->fails()) {
            return response()->json([
                'message' => $userProfileValidator->errors()->first(),
                'status' => false,
            ], 400);
        }

        try {
            DB::beginTransaction();
            $user = Users::insert($request);
            $request['user_id'] = $user->id;
            $userProfile = UsersProfile::insert($request);
            DB::commit();

            $payload = [
                'id' => $user->id,
                'role' => Roles::getRoleValue($user->role_id),
                'email' => $user->email,
                'name' => $userProfile->name,
            ];
            $token = JWTController::createToken($payload);

            return response()->json([
                'token' => $token,
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan!',
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
            $userProfile = UsersProfile::getUserProfileByUserId($user->id);

            $payload = [
                'id' => $user->id,
                'role' => Roles::getRoleValue($user->role_id),
                'email' => $user->email,
                'name' => $userProfile->name,
            ];
            $token = JWTController::createToken($payload);

            return response()->json([
                'token' => $token,
                'status' => true,
            ]);
        }
        catch (\Throwable $th) {
            return response()->json([
                'message' => 'Terjadi kesalahan!',
                'status' => false,
            ], 400);
        }
    }
}
