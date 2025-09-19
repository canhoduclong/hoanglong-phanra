<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $managerId = \App\Models\User::where('email', 'manager@example.com')->value('id');
        $staffId = \App\Models\User::where('email', 'staff@example.com')->value('id');
        $adminId = \App\Models\User::where('email', 'admin@example.com')->value('id');
        DB::table('orders')->insert([
            [
                'customer_id' => 1,
                'user_id' => $managerId,
                'code' => 'ORD001',
                'total' => 1500000,
                'status' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 2,
                'user_id' => $staffId,
                'code' => 'ORD002',
                'total' => 2500000,
                'status' => 'processing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => 3,
                'user_id' => $adminId,
                'code' => 'ORD003',
                'total' => 3500000,
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
