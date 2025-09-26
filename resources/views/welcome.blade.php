@extends('layouts.site')



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