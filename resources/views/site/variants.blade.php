@extends('layouts.site')

@section('content')
<div class="container">
    <h1>Product Variants</h1>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('site.variants') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Total Order Value: <span id="total-order-value">0</span>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($variants as $variant)
                    <tr>
                        <td>{{ $variant->product->name }}</td>
                        <td>{{ $variant->sku }}</td>
                        <td>{{ number_format($variant->latestPriceRule?->price ?? 0) }}</td>
                        <td>{{ $variant->stock }}</td>
                        <td>
                            <button class="btn btn-success btn-sm order-btn" data-price="{{ $variant->latestPriceRule?->price ?? 0 }}">Order</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $variants->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let totalOrderValue = 0;
        const totalOrderValueEl = document.getElementById('total-order-value');

        document.querySelectorAll('.order-btn').forEach(button => {
            button.addEventListener('click', function() {
                const price = parseFloat(this.dataset.price);
                totalOrderValue += price;
                totalOrderValueEl.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(totalOrderValue);
                // Here you can add the logic to add the variant to the cart
                alert('Ordered! Total value: ' + totalOrderValue);
            });
        });
    });
</script>
@endpush
