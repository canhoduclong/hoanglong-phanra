<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderApproval;
use App\Workflows\OrderWorkflow;
use App\Enums\OrderStatus;
use App\Models\User;

class OrderService
{
    public function updateStatus(Order $order, string $newStatus, User $user): Order
    {
        $workflow = new OrderWorkflow();

        // Kiểm tra transition hợp lệ
        if (! $workflow->canTransition($order->status, $newStatus)) {
            throw new \Exception("Không thể chuyển trạng thái từ {$order->status} sang {$newStatus}");
        }

        // Kiểm tra role
        if ($newStatus === OrderStatus::LeaderConfirmed->value && ! $user->hasRole('leader')) {
            throw new \Exception("Chỉ Leader mới được quyền xác nhận");
        }

        if ($newStatus === OrderStatus::ManagerConfirmed->value && ! $user->hasRole('manager')) {
            throw new \Exception("Chỉ Manager mới được quyền xác nhận");
        }

        // Cập nhật trạng thái
        $order->status = $newStatus;
        $order->save();

        // Lưu log vào bảng approvals
        OrderApproval::create([
            'order_id' => $order->id,
            'user_id'  => $user->id,
            'role' => $user->roles()->first()->name ?? 'no-role',
            'status'   => $newStatus,
            'note'     => null,
        ]);

        return $order;
    }
}
