@extends('layouts.admin')

@section('title', 'Create Warehouse')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Warehouse</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">New Warehouse Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('warehouses.store') }}" method="POST">
                @csrf
                @include('warehouses._form')
            </form>
        </div>
    </div>
</div>
@endsection
