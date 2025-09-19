<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dữ liệu thực tế export từ database
        $categories = [
            [
                'name' => 'Điện tử',
                'slug' => null,
                'description' => 'Các sản phẩm điện tử và công nghệ',
                'parent_id' => null,
                'image' => 'images/categories/electronics.jpg',
            ],
            [
                'name' => 'Thời trang',
                'slug' => null,
                'description' => 'Trang phục, giày dép, phụ kiện thời trang',
                'parent_id' => null,
                'image' => 'images/categories/fashion.jpg',
            ],
            [
                'name' => 'Đồ gia dụng',
                'slug' => null,
                'description' => 'Các sản phẩm dùng trong gia đình',
                'parent_id' => null,
                'image' => 'images/categories/home.jpg',
            ],
            [
                'name' => 'Điện thoại',
                'slug' => null,
                'description' => 'Smartphone và phụ kiện',
                'parent_id' => 1,
                'image' => 'images/categories/phone.jpg',
            ],
            [
                'name' => 'Laptop',
                'slug' => null,
                'description' => 'Máy tính xách tay các loại',
                'parent_id' => 1,
                'image' => 'images/categories/laptop.jpg',
            ],
            [
                'name' => 'Nam',
                'slug' => null,
                'description' => 'Thời trang nam',
                'parent_id' => 2,
                'image' => 'images/categories/men.jpg',
            ],
            [
                'name' => 'Nữ',
                'slug' => null,
                'description' => 'Thời trang nữ',
                'parent_id' => 2,
                'image' => 'images/categories/women.jpg',
            ],
            [
                'name' => 'Nhà bếp',
                'slug' => null,
                'description' => 'Dụng cụ và thiết bị nhà bếp',
                'parent_id' => 3,
                'image' => 'images/categories/kitchen.jpg',
            ],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
