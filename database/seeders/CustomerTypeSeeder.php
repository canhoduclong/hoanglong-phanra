<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('customer_types')->insert([
            ['name' => 'Vip', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gold', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Normal', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
