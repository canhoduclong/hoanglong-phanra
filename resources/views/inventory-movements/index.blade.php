@extends('layouts.app')

@section('title', 'Inventory Movements')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Inventory Movements</h1>
        <a href="{{ route('inventory-movements.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Add Inventory Movement</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Inventory Movement List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Inventory</th>
                            <th>Quantity</th>
                            <th>Type</th>
                            <th>Reference</th>
                            <th>User</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inventoryMovements as $movement)
                            <tr>
                                <td>{{ $movement->inventory->productVariant->sku }} @ {{ $movement->inventory->warehouse->name }}</td>
                                <td>{{ $movement->quantity }}</td>
                                <td>{{ $movement->type }}</td>
                                <td>{{ $movement->reference_type }}: {{ $movement->reference_id }}</td>
                                <td>{{ $movement->user->name ?? 'N/A' }}</td>
                                <td>{{ $movement->created_at }}</td>
                                <td>
                                    <form action="{{ route('inventory-movements.destroy', $movement->id) }}" method="POST">
                                        <a href="{{ route('inventory-movements.edit', $movement->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No inventory movements found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $inventoryMovements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
