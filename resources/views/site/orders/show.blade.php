@extends('layouts.site')

@section('content')
<div class="container">
    <h1>Order Details</h1>

    <div class="card">
        <div class="card-header">
            Order #{{ $order->id }}
        </div>
        <div class="card-body">
            <p><strong>Customer:</strong> {{ $order->customer->name }}</p>
            <p><strong>Status:</strong> {{ $order->status }}</p>
            <p><strong>Payment Status:</strong> {{ $order->payment_status }}</p>
            <p><strong>Delivery Status:</strong> {{ $order->delivery_status }}</p>
            <p><strong>Total Amount:</strong> {{ number_format($order->total_amount) }}</p>

            <h5 class="mt-4">Order Items</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Variant</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->variant->product->name }}</td>
                        <td>{{ $item->variant->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price) }}</td>
                        <td>{{ number_format($item->price * $item->quantity) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('pages.my_orders') }}" class="btn btn-primary">Back to My Orders</a>
        </div>
    </div>
</div>
@endsection