<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public static function getRoleValue($id)
    {
        return self::query()->where('id', $id)->pluck('role')->first();
    }
}
