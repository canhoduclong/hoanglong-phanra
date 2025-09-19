<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = \DB::table('products')->get();
        $data = [];
        $skuBase = 'SKU';
        foreach ($products as $i => $product) {
            for ($j = 1; $j <= 2; $j++) {
                // Giá mặc định cho biến thể, có thể lấy từ product hoặc random
                $defaultPrice = $product->price ? $product->price + rand(-100000, 100000) : rand(100000, 1000000);
                $data[] = [
                    'product_id' => $product->id,
                    'sku' => $skuBase . ($i+1) . $j,
                    'size' => $j == 1 ? 'M' : 'L',
                    'quality' => $j == 1 ? 'Loại 1' : 'Loại 2',
                    'production_date' => now()->subMonths($j),
                    'stock' => rand(5, 20),
                    'price' => $defaultPrice,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        \DB::table('product_variants')->insert($data);
    }
}
