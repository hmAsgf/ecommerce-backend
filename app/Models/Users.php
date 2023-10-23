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
}
