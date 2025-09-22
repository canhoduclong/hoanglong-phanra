@extends('layouts.app')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Danh sách biến thể sản phẩm</h4>
        <a href="{{ route('product-variants.create') }}" class="btn btn-success">+ Thêm biến thể mới</a>
    </div>
    <form method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Tìm kiếm SKU, size, chất lượng, tên sản phẩm..." value="{{ request('q') }}">
            <button class="btn btn-primary">Tìm kiếm</button>
        </div>
    </form>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ảnh</th>
                <th>SKU</th>
                <th>Sản phẩm</th>
                <th>Size</th>
                <th>Chất lượng</th>
                <th>Ngày SX</th>
                <th>Giá bán</th>
                <th>Tồn kho</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($variants as $v)
            <tr>
                <td>{{ $v->id }}</td>
                <td> 
                    @if($v->media)
                        <img src="{{ asset('storage/' . $v->media->file_path) }}" width="50" class="rounded">
                    @endif
                </td>
                <td>{{ $v->sku }}</td>
                <td>{{ $v->product->name ?? '' }}</td>
                <td>{{ $v->size }}</td>
                <td>{{ $v->quality }}</td>
                <td>{{ $v->production_date }}</td>
                <td>
                    @php
                        $latestPrice = $v->latestPriceRule ? $v->latestPriceRule->price : $v->final_price;
                    @endphp
                    {{ number_format($latestPrice ?? 0, 0, ',', '.') }} đ
                </td>
                <td>{{ $v->stock }}</td>
                <td>
                    <a href="{{ route('product-variants.edit', $v->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <a href="{{ route('variants.edit-price', $v->id) }}?from=product-variants" class="btn btn-sm btn-info mt-1">Điều chỉnh giá</a>
                    <button type="button" class="btn btn-sm btn-primary mt-1 clone-variant-index" data-variant-id="{{ $v->id }}" data-variant='@json($v)'>Nhân bản</button>
                    <button type="button" class="btn btn-sm btn-success mt-1 quick-edit-variant-index" data-variant-id="{{ $v->id }}">Sửa nhanh</button>
                </td>
            </tr>
            @endforeach
    </tbody>
    </table>
    <div>
        {{ $variants->links() }}
    </div>
</div>
@endsection
