<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $table = 'products';
        Schema::disableForeignKeyConstraints();
        DB::table($table)->truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            ['category_id' => 1, 'name' => 'Nasi Goreng Jancuk', 'price' => '15000'],
            ['category_id' => 1, 'name' => 'Nasi Goreng Kambing', 'price' => '17000'],
            ['category_id' => 1, 'name' => 'Nasi Goreng Sosis', 'price' => '17000'],
            ['category_id' => 1, 'name' => 'Nasi Goreng Jawir', 'price' => '15000'],
            ['category_id' => 1, 'name' => 'Nasi Goreng Seafood', 'price' => '17000'],
            ['category_id' => 1, 'name' => 'Nasi Goreng Merah', 'price' => '15000'],
            ['category_id' => 2, 'name' => 'Es Teh', 'price' => '7000'],
            ['category_id' => 2, 'name' => 'Es Jeruk', 'price' => '7000'],
            ['category_id' => 3, 'name' => 'Kentang Goreng', 'price' => '10000'],
            ['category_id' => 3, 'name' => 'Tahu Crispy', 'price' => '7000'],
        ];

        foreach ($data as $item) {
            DB::table($table)->updateOrInsert($item);
        };
    }
}
