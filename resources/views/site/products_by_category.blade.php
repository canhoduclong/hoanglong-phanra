@extends('layouts.site')

@section('header')
<header class="p-3 mb-3 border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                @if(isset($settings['logo']) && $settings['logo']->value)
                    @php
                        $media = App\Models\Media::find($settings['logo']->value);
                    @endphp
                    @if($media)
                        <img src="{{ asset('storage/' . $media->file_path) }}" alt="logo" height="40">
                    @endif
                @else
                    <h2>{{ $settings['brand_name']->value ?? 'My Website' }}</h2>
                @endif
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#" class="nav-link px-2 link-secondary">Home</a></li>
            </ul>

            <div class="text-end">
                <p class="slogan">{{ $settings['slogan']->value ?? 'Your slogan here' }}</p>
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                @endauth
            </div>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <h4>Categories</h4>
            <div class="list-group">
                <a href="{{ route('pages.products_by_category', ['category' => null, 'date' => request('date')]) }}" class="list-group-item list-group-item-action {{ !isset($category) ? 'active' : '' }}">
                    All Categories
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('pages.products_by_category', ['category' => $cat->slug, 'date' => request('date')]) }}" class="list-group-item list-group-item-action {{ (isset($category) && $category->id == $cat->id) ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
        <div class="col-md-9">
            <h1>Product Variants</h1>

            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('pages.products_by_category', ['category' => $category->slug ?? null]) }}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Filter by Date</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                @foreach($variants as $variant)
                @if($variant->slug)
                <div class="col-md-3 mb-4">
                    <div class="card">
                       
                        <a href="{{ route('pages.variant_detail', $variant->slug) }}">
                             
                            @if($variant->product->avatar && $variant->product->avatar->media)
                            
                                <img src="{{ asset('storage/' . $variant->product->avatar->media->file_path) }}" class="card-img-top" alt="{{ $variant->product->name }}">
                            @else
                                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="placeholder">
                            @endif
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><a href="{{ route('pages.variant_detail', $variant->slug) }}">{{ $variant->product->name }}</a></h5>
                            <p class="card-text">SKU: {{ $variant->sku }}</p>
                            <p class="card-text">Price: {{ number_format($variant->latestPriceRule?->price ?? 0) }}</p>
                            <p class="card-text">Stock: {{ $variant->stock }}</p>
                            <a href="{{ route('pages.variant_detail', $variant->slug) }}" class="btn btn-info btn-sm">View</a>
                            <button class="btn btn-success btn-sm order-btn" data-price="{{ $variant->latestPriceRule?->price ?? 0 }}">Order</button>
                            @can('update', $variant)
                                <a href="{{ route('product-variants.edit', $variant->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            @endcan
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>

            {{ $variants->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@section('footer')
<footer class="footer mt-auto py-3 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Thông tin công ty</h5>
                <p>{{ $settings['brand_name']->value ?? '' }}</p>
                <p>Mã số thuế: {{ $settings['tax_number']->value ?? 'Chưa có' }}</p>
            </div>
            <div class="col-md-4">
                <h5>Liên hệ</h5>
                <p>Địa chỉ: {{ $settings['address']->value ?? '' }}</p>
                <p>Hotline: {{ $settings['hotline']->value ?? '' }}</p>
                <p>Email: {{ $settings['email']->value ?? '' }}</p>
            </div>
            <div class="col-md-4">
                <h5>Chính sách</h5>
                <p><a href="{{ $settings['policy_page']->value ?? '#' }}">Chính sách và quy định</a></p>
            </div>
        </div>
    </div>
</footer>
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
                if(totalOrderValueEl) {
                    totalOrderValueEl.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(totalOrderValue);
                }
                // Here you can add the logic to add the variant to the cart
                alert('Ordered! Total value: ' + totalOrderValue);
            });
        });
    });
</script>
@endpush