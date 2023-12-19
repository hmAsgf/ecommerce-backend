<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Users extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $guarded = ['id'];
    protected $hidden = [
        'password',
        'created_at', 'updated_at',
    ] ;

    public static function loginValidate($data)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];

        return Validator::make($data, $rules);
    }

    public static function registerValidate($data)
    {
        $rules = [
            'role_id' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'confirmPassword' => 'required|same:password',
        ];

        return Validator::make($data, $rules);
    }

    public static function getUserByEmail($email)
    {
        return self::query()->where('email', $email)->first();
    }

    public static function loginGoogle($user)
    {
        $findUser = self::query()->where('google_id',$user->id)->first();

        if(!$findUser) {
            $newUser = self::create([
                            'role_id' => 2,
                            'google_id' => $user->id,
                            'email' => $user->email,
                        ]);

            UsersProfile::loginGoogle($newUser->id, $user);

            return $newUser;
        }

        return $findUser;
    }

    public static function insert($user)
    {
        $user['password'] = Hash::make($user['password']);
        return self::query()->create($user->all());
    }

    public static function passwordValidate($data)
    {
        $rules = [
            'old_password' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ];

        return Validator::make($data, $rules);
    }

    public static function modifyPassword($id, $newPassword)
    {
        return self::query()->find($id)
                            ->update(['password' => bcrypt($newPassword)]);
    }

    public static function getById($id)
    {
        return self::query()->where('id',$id)->get()->first();
    }

}
