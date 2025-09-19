@extends('layouts.app')
@section('content')
<div class="container">
    <h4>Quản lý đơn hàng</h4>
    <button class="btn btn-success mb-3" id="add-order">+ Thêm đơn hàng</button>
    <div id="orders-list-table"></div>
</div>
@push('scripts')
<script>
function loadOrders() {
    $.get("{{ route('orders.list-ajax') }}", function(html) {
        $('#orders-list-table').html(html);
    });
}
$(function() {
    loadOrders();
    $('#orders-list-table').on('click', '.btn-toggle-status', function() {
        let id = $(this).data('id');
        $.post(`/orders/${id}/toggle-status`, {_token: '{{ csrf_token() }}'}, function() {
            loadOrders();
        });
    });
    $('#orders-list-table').on('click', '.delete-order', function() {
        if(confirm('Xóa đơn hàng này?')) {
            let id = $(this).data('id');
            $.ajax({url: `/orders/${id}`, type: 'DELETE', data: {_token: '{{ csrf_token() }}'}, success: loadOrders});
        }
    });
    // Sửa đơn hàng: mở modal hoặc chuyển trang tuỳ ý
    $('#orders-list-table').on('click', '.edit-order', function() {
        let id = $(this).data('id');
        window.location.href = `/orders/${id}/edit`;
    });
    $('#add-order').on('click', function() {
        window.location.href = `/orders/create`;
    });
});
</script>
@endpush
@endsection
