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
    <div class="jumbotron">
        <h1 class="display-4">Chào mừng đến với {{ $settings['brand_name']->value ?? 'Công ty chúng tôi' }}!</h1>
        <p class="lead">Chúng tôi chuyên cung cấp các sản phẩm và dịch vụ chất lượng cao. Hãy khám phá trang web của chúng tôi để biết thêm chi tiết.</p>
        <hr class="my-4">
        <a class="btn btn-primary btn-lg" href="{{ route('site.variants') }}" role="button">Xem sản phẩm</a>
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