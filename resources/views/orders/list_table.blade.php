<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Mã đơn</th>
            <th>Khách hàng</th>
            <th>Nhân viên</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Trạng thái thanh toán</th>
            <th>Ngày tạo</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->code }}</td>
            <td>{{ $order->customer->name ?? '' }}</td>
            <td>{{ $order->user->name ?? '' }}</td>
            <td>{{ number_format($order->total, 0, ',', '.') }} đ</td>
            <td>
                <button class="btn btn-sm btn-toggle-status" data-id="{{ $order->id }}" data-status="{{ $order->status }}">
                    {{ $order->status }}
                </button>
            </td>
            <td>
                @if($order->status === \App\Models\Order::STATUS_COMPLETED)
                    <span class="badge bg-success">Đã hoàn thành</span>
                @elseif($order->isPaid())
                    <span class="badge bg-success">Đã thanh toán đủ</span>
                @elseif($order->isPartialPaid())
                    <span class="badge bg-warning text-dark">Thanh toán một phần</span>
                @else
                    <span class="badge bg-danger">Chưa thanh toán</span>
                @endif
            </td>
            <td>{{ $order->created_at }}</td>
            <td>
                @if(!$order->isPaid() && $order->status !== \App\Models\Order::STATUS_COMPLETED)
                    <button class="btn btn-info btn-sm edit-order" data-id="{{ $order->id }}">Sửa</button>
                @endif
                <button class="btn btn-danger btn-sm delete-order" data-id="{{ $order->id }}">Xóa</button>
                @if($order->status !== \App\Models\Order::STATUS_COMPLETED && !$order->isPaid())
                    <a href="{{ route('transactions.create', ['order_id' => $order->id]) }}" class="btn btn-success btn-sm">Thanh toán</a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
