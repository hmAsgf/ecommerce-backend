<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CartsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $table = 'carts';
        Schema::disableForeignKeyConstraints();
        DB::table($table)->truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            ['user_id' => '1'],
            ['user_id' => '2'],
            ['user_id' => '3'],
        ];

        foreach ($data as $item) {
            DB::table($table)->updateOrInsert($item);
        }
    }
}
