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
                    @if(Setting::get('logo'))
                        <img src="{{ asset('storage/' . Setting::get('logo')) }}" alt="logo" height="40">
                    @else
                        <h2>{{ Setting::get('brand_name', 'My Website') }}</h2>
                    @endif
                </a>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="#" class="nav-link px-2 link-secondary">Home</a></li>
                </ul>

                <div class="text-end">
                    <p class="slogan">{{ Setting::get('slogan', 'Your slogan here') }}</p>
                </div>
            </div>
        </div>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Address</h5>
                    <p>{{ Setting::get('address') }}</p>
                </div>
                <div class="col-md-4">
                    <h5>Hotline</h5>
                    <p>{{ Setting::get('hotline') }}</p>
                </div>
                <div class="col-md-4">
                    <h5>Email</h5>
                    <p>{{ Setting::get('email') }}</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
