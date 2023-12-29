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

    public static function registerValidate($data, $id=null)
    {
        $rules = [
            'name' => 'required',
            'birth_date' => 'required',
            'phone_number' => "required|unique:users_profile,phone_number,$id",
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

    public static function insert($userProfile)
    {
        return self::query()->create($userProfile->all());
    }

    public static function modify($id, $userProfile)
    {
        return self::query()->where("user_id", $id)
                            ->update($userProfile->all());
    }

    public static function getById($id)
    {
        return self::select('users.id','users.email','users_profile.name','users_profile.birth_date','users_profile.phone_number','users_profile.address')
            ->LeftJoin('users','users_profile.user_id','users.id')
            ->where('users.id', $id)
            ->get()
            ->first();
    }
}
