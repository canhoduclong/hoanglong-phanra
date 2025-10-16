@extends('layouts.site')

@section('content')
<div class="container">
    <h1>Tạo đơn hàng mới</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('orders.store_new') }}" method="POST">
        @csrf
        <input type="hidden" name="variant_id" value="{{ $variant->id }}">

        <div class="card mb-3">
            <div class="card-header">Thông tin sản phẩm</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        @if($variant->media)
                            <img src="{{ asset('storage/' . $variant->media->file_path) }}" alt="{{ $variant->product->name }}" class="img-fluid">
                        @elseif($variant->product->avatar && $variant->product->avatar->media)
                            <img src="{{ asset('storage/' . $variant->product->avatar->media->file_path) }}" alt="{{ $variant->product->name }}" class="img-fluid">
                        @else
                            <img src="https://via.placeholder.com/150" alt="placeholder" class="img-fluid">
                        @endif
                    </div>
                    <div class="col-md-10">
                        <h5>{{ $variant->product->name }}</h5>
                        <p>SKU: {{ $variant->sku }}</p>
                        <p>Giá: {{ number_format($variant->latestPriceRule?->price ?? 0) }}</p>
                        <p>Tồn kho: {{ $variant->stock }}</p>
                        
                        <div class="form-group">
                            <label for="quantity">Số lượng</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="{{ $variant->stock }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Thông tin khách hàng và đơn hàng</div>
            <div class="card-body">
                <div class="form-group">
                    <h5>Chọn khách hàng</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Chọn</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="radio" name="customer_id" id="customer_{{ $customer->id }}" value="{{ $customer->id }}" required>
                                    </td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->phone }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $customers->links() }}
                    </div>
                </div>

               

                <button type="submit" class="btn btn-primary">Lên đơn</button>
            </div>
        </div>

    </form>
</div>
@endsection
