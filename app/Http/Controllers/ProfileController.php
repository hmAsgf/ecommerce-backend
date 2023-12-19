<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\UsersProfile;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show($id)
    {
        $userProfile = UsersProfile::getById($id);

        if(!$userProfile) {
            return response()->json([
                'message' => 'User tidak ditemukan!',
                'status' => false,
            ],400);
        }

        return response()->json([
            'profile' => $userProfile,
            'status' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = UsersProfile::registerValidate($request->all(), $id);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => false,
            ],400);
        }

        $userProfile = UsersProfile::modify($id, $request);

        if(!$userProfile){
            return response()->json([
                'message' => 'User gagal diubah!',
                'status' => false,
            ], 400);
        }

        return response()->json([
            'message' => 'User berhasil diubah!',
            'status' => true,
        ]);
    }

    public function updatePassword(Request $request, $id)
    {
        // $data=$request->all();
        $password_validator = Users::passwordValidate($request->all());

        if($password_validator->fails()){
            return response()->json([
                'message' => $password_validator->errors()->first(),
                'status' => false,
            ],400);
        }

        $account = Users::getById($id);
        
        if($account && Hash::check($request['old_password'], $account->password))
        {
            $check = Users::modifyPassword($account->id, $request['password']);
            
            if(!$check){
                return response()->json([
                    'message' => 'Password gagal diubah!',
                    'status' => false,
                ],400);
            }
            return response()->json([
                'message' => 'Password berhasil diubah!',
                'status' => true,
            ]);
        }
        return response()->json([
            'message' => 'Password lama salah!',
            'status' => false,
        ], 400);
        
    }
}
