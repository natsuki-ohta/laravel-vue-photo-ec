<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'category_id' => 1,
                'name' => '東京の朝',
                'price' => 500,
                'stock' => 10,
                'description' => '東京の日常風景',
                'image_path' => null,
                'is_public' => true,
            ],
            [
                'category_id' => 2,
                'name' => '上海の夜',
                'price' => 700,
                'stock' => 5,
                'description' => '中国都市の夜景',
                'image_path' => null,
                'is_public' => true,
            ],
            [
                'category_id' => 3,
                'name' => '金沢八景',
                'price' => 300,
                'stock' => 5,
                'description' => '金沢八景の海',
                'image_path' => null,
                'is_public' => true,
            ],
            [
                'category_id' => 4,
                'name' => 'その他photo1',
                'price' => 350,
                'stock' => 5,
                'description' => 'その他photo1',
                'image_path' => null,
                'is_public' => true,
            ],
        ]);
    }
}
