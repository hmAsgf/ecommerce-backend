<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UsersProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $table = 'users_profile';
        Schema::disableForeignKeyConstraints();
        DB::table($table)->truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            [
                'user_id' => 1, 'name' => 'Ibrahim', 'birth_date' => '2003-01-06', 'age' => 20,
                'phone_number' => '081231270544', 'address' => 'Sidodadi Kulon Gg I, Surabaya'
            ],
            [
                'user_id' => 2, 'name' => 'Teguh Firdaus Alfaraih', 'birth_date' => '2003-01-06', 'age' => 20,
                'phone_number' => '081235981551', 'address' => '..., Sidoarjo'
            ],
            [
                'user_id' => 2, 'name' => 'M. Mahameru. A', 'birth_date' => '2003-01-06', 'age' => 20,
                'phone_number' => '085646452991', 'address' => '..., Sidoarjo'
            ],
        ];

        foreach ($data as $item) {
            DB::table($table)->updateOrInsert($item);
        };
    }
}
