@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Website Settings</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="brand_name" class="form-label">Brand Name</label>
            <input type="text" class="form-control" id="brand_name" name="brand_name" value="{{ $settings['brand_name']->value ?? '' }}">
        </div>

        <div class="mb-3">
            <label for="slogan" class="form-label">Slogan</label>
            <input type="text" class="form-control" id="slogan" name="slogan" value="{{ $settings['slogan']->value ?? '' }}">
        </div>

        <div class="mb-3">
            <label for="logo" class="form-label">Logo</label>
            <input class="form-control" type="file" id="logo" name="logo">
            @if(isset($settings['logo']) && $settings['logo']->value)
                <img src="{{ asset('storage/' . $settings['logo']->value) }}" alt="logo" width="150" class="mt-2">
            @endif
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3">{{ $settings['address']->value ?? '' }}</textarea>
        </div>

        <div class="mb-3">
            <label for="hotline" class="form-label">Hotline</label>
            <input type="text" class="form-control" id="hotline" name="hotline" value="{{ $settings['hotline']->value ?? '' }}">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $settings['email']->value ?? '' }}">
        </div>

        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>
@endsection
