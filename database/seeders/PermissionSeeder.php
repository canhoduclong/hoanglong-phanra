<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            $name = $route->getName();
            if (!$name) { continue;}
            $group = explode('.', $name)[0] ?? null;
            DB::table('permissions')->updateOrInsert(
                ['name' => $name],
                [
                    'description' => $route->uri(),
                    'group' => $group,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        // Bổ sung quyền giao dịch
        $transactionPermissions = [
            ['name' => 'transaction.view', 'description' => 'Xem giao dịch', 'group' => 'transaction'],
            ['name' => 'transaction.create', 'description' => 'Tạo giao dịch', 'group' => 'transaction'],
            ['name' => 'transaction.refund', 'description' => 'Hoàn trả giao dịch', 'group' => 'transaction'],
        ];
        foreach ($transactionPermissions as $perm) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $perm['name']],
                [
                    'description' => $perm['description'],
                    'group' => $perm['group'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
        // Gán tất cả quyền cho role admin
        $adminRole = DB::table('roles')->where('name', 'admin')->first();
        if ($adminRole) {
            $permissionIds = DB::table('permissions')->pluck('id')->toArray();
            foreach ($permissionIds as $pid) {
                DB::table('permission_role')->updateOrInsert([
                    'role_id' => $adminRole->id,
                    'permission_id' => $pid
                ]);
            }
        }
    }
}
