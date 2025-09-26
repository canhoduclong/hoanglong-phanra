@php
use App\Models\Setting;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ Setting::get('brand_name', 'My Website') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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
                    <li><a href="{{ route('home') }}" class="nav-link px-2 link-secondary">Trang chủ</a></li>
                    <li><a href="{{ route('pages.about') }}" class="nav-link px-2 link-dark">Giới thiệu</a></li>
                    <li><a href="{{ route('site.variants') }}" class="nav-link px-2 link-dark">Sản phẩm</a></li> 
                    <li><a href="{{ route('posts.list') }}" class="nav-link px-2 link-dark">Tin tức</a></li>
                    <li><a href="{{ route('pages.contact') }}" class="nav-link px-2 link-dark">Liên hệ</a></li>
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

    <main class="container">
        @yield('content')
    </main>

    @yield('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>