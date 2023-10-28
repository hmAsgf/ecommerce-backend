<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public static function validate($data)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required',
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
}
