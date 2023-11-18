<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class UsersProfile extends Model
{
    use HasFactory;

    protected $table = 'users_profile';
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public static function registerValidate($data)
    {
        $rules = [
            'name' => 'required',
            'birth_date' => 'required',
            'age' => 'required',
            'phone_number' => 'required|unique:users_profile,phone_number',
            'address' => 'required',
        ];

        return Validator::make($data, $rules);
    }

    public static function loginGoogle($id, $user)
    {
        return self::create([
            'user_id' => $id,
            'name' => $user->name,
        ]);
    }

    public static function getUserProfileByUserId($id)
    {
        return self::query()->where('user_id', $id)->first();
    }

    public static function insert($userProfile)
    {
        return self::query()->create($userProfile->all());
    }
}
