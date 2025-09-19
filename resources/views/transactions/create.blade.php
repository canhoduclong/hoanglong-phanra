@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Thêm giao dịch</h2>
    <form action="{{ route('transactions.store') }}" method="POST">
        @csrf
        @if(request('order_id'))
            <input type="hidden" name="order_id" value="{{ request('order_id') }}">
            <div class="mb-3">
                <label>Đơn hàng</label>
                <input type="text" class="form-control" value="#{{ request('order_id') }}" disabled>
            </div>
        @else
            <div class="mb-3">
                <label>Đơn hàng (nếu có)</label>
                <select name="order_id" class="form-select" id="order_id_select">
                    <option value="">-- Không liên kết --</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}">#{{ $order->code }} - {{ $order->customer->name ?? '' }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="mb-3" id="order_total_box" style="display:none;">
            <label>Tổng tiền đơn hàng: <span id="order_total_text" class="fw-bold text-danger"></span></label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pay_full_order">
                <label class="form-check-label" for="pay_full_order">Thanh toán toàn bộ</label>
            </div>
        </div>
        <div class="mb-3">
            <label>Khách hàng (nếu có)</label>
            <div class="input-group">
                <input type="text" id="customer_name" class="form-control" placeholder="Chọn khách hàng" readonly>
                <input type="hidden" name="customer_id" id="customer_id">
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#customerModal">Chọn khách hàng</button>
            </div>
        </div>
        <div class="mb-3">
            <label>Số tiền</label>
            <input type="number" step="0.01" name="amount" class="form-control" id="amount_input" required>
        </div>
        <div class="mb-3">
            <label>Loại giao dịch</label>
            <select name="type" class="form-select" required>
                <option value="payment">Thanh toán</option>
                <option value="refund">Hoàn trả</option>
                <option value="fee">Chi phí</option>
                <option value="extra_income">Thu khác</option>
                <option value="extra_expense">Chi khác</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Phương thức</label>
            <input type="text" name="method" class="form-control">
        </div>
        <div class="mb-3">
            <label>Ghi chú</label>
            <input type="text" name="note" class="form-control">
        </div>
        <button class="btn btn-primary">Lưu giao dịch</button>
    </form>
</div>
@include('customers.popup_select')
@endsection
