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
        $table = 'cart_items';
        Schema::disableForeignKeyConstraints();
        DB::table($table)->truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            ['user_id' => 2, 'product_id' => 6, 'quantity' => 1, 'sub_total' => 15000],
            ['user_id' => 2, 'product_id' => 9, 'quantity' => 2, 'sub_total' => 20000],
            ['user_id' => 2, 'product_id' => 8, 'quantity' => 1, 'sub_total' => 7000],
        ];

        foreach ($data as $item) {
            DB::table($table)->updateOrInsert($item);
        }
    }
}
