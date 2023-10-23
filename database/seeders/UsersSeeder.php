<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $table = 'users';
        Schema::disableForeignKeyConstraints();
        DB::table($table)->truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            ['role_id' => 1, 'email' => 'admin@ecommerce.com', 'password' => Hash::make('admin99')],
            ['role_id' => 2, 'email' => 'teguh@ecommerce.com', 'password' => Hash::make('teguh')],
            ['role_id' => 2, 'email' => 'mahameru@ecommerce.com', 'password' => Hash::make('mahameru')],
        ];

        foreach ($data as $item) {
            DB::table($table)->updateOrInsert($item);
        };
    }
}
