<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {   
        $this->call([
            UsersSeeder::class,
            PermissionSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            CustomerTypeSeeder::class,
            CustomerSeeder::class,
            ProductVariantSeeder::class,
            ContactSeeder::class,
            LeadSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            TaskSeeder::class,
        ]);
    }
}
