@extends('layouts.app', [
    'menu' => 'product',
])

@section('content')


<div class="content"  id="ProductList">
<h2>Danh sách sản phẩm</h2> 
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

 <form action="{{ route('products.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="name" class="form-control" placeholder="Tìm kiếm theo tên sản phẩm..." value="{{ request('name') }}">
            <select name="category_id" class="form-control">
                <option value="">Tất cả danh mục</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @if(request('category_id') == $category->id) selected @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Tìm kiếm</button>
            </div>
        </div>
    </form>
    
    @can('create', App\Models\Product::class)
        <a href="{{ route('products.create') }}" class="btn btn-success mb-3">{{ __('admin.product.create') }}</a>
    @endcan 



    <div class="card"> 
        <div class="card-header">
            <h5 class="mb-0">{{ __('admin.product.list') }}</h5>
        </div>

        <div class="card-body d-flex justify-content-between"> 
            <div class="filter-area">  
                <div class="input-group">
                    <input type="text" list-control="search-input" class="form-control" placeholder="{{ __('admin.product.name') }}"> 
                    <a href="#" list-control="search-button" class="btn btn-secondary" >
                        <span class="material-symbols-rounded" style="line-height: 1 !important;">{{ __('admin.product
                            .search') }}</span> 
                    </a>
                </div>
            </div>
            
            @if(auth()->user()->hasPermission('add'))
                <a class="btn btn-outline-success btn-sm" href="{{ route('products.create') }}">
                    <i class="ph-plus ph-sm me-2"></i>
                    {{ __('admin.product.create') }}
                </a> 
            @endif

        </div> 
      
        
        <div class="product-container product-bdr">
    <table class="table border product-list-table"> 
        <thead class="product-header-bg">
            <tr>
                <th>
                    <span class="d-flex align-items-center padding-cell pl-0">
                        <span>{{ __('admin.product.image') }}</span>
                    </span>
                </th> 
                <th  style="text-align: center"> 
                    <span class="d-flex align-items-center padding-cell pl-0">
                        <span>{{ __('admin.product.name') }}</span>
                        <span class="column-controls ms-auto">
                            <span
                                list-action="sort"
                                sort-by="name"
                                sort-direction="{{ $sort_by == 'name' ? $sort_direction : 'asc' }}"
                                class="list_column_action ms-2 {{ $sort_by == 'name' ? 'active' : '' }}">
                                <i class="ph ph-funnel-simple"></i>
                            </span>
                        </span>
                    </span>
                </th>
                <th>
                    <span class="d-flex align-items-center padding-cell pl-0">
                        <span>{{ __('admin.product.category') }}</span> 
                    </span>
                </th>
                <th>
                    <span class="d-flex align-items-center padding-cell pl-0">
                        <span>{{ __('admin.product.stock') }}</span>
                        <span class="column-controls ms-auto">
                            <span
                                list-action="sort"
                                sort-by="price"
                                sort-direction="{{ $sort_by == 'price' ? $sort_direction : 'asc' }}"
                                class="list_column_action ms-2 {{ $sort_by == 'price' ? 'active' : '' }}">
                                <i class="ph ph-funnel-simple"></i>
                            </span>
                        </span>
                    </span>
                </th>
                
                <th class="text-center" >
                    <div class="padding-cell">
                        {{ __('admin.actions') }} <i class="ph-arrow-circle-dowsn"></i>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody> 
            @foreach($products as $key => $product)  
            <tr>
                <td width="1%">
                   
                    @if($product->avatar && $product->avatar->media)
                        <img src="{{ asset('storage/' . $product->avatar->media->file_path) }}" width="80">
                    @else
                        <span>No image</span>
                    @endif

                </td>  
                <td>{{ $product->name ?? '' }}</td> 
                <td>{{ $product->category->name ??'' }}</td>
                <td>{{ $product->stock ?? '' }}</td>
                <td>

                    @if(auth()->user()->hasPermission('edit'))
                        <a href="{{ route('products.edit', ['product' => $product->id, 'page' =>  request()->page, 'perPage' => $perPage] ) }}" class="btn btn-warning btn-sm me-1">
                             <i class="ph ph-pencil-line"></i>
                        </a>
                    @endif
 
                

                    <div class="d-flex justify-content-end list-actions"> 
                         @can('update', $product)
                            <a href="{{ route('products.edit', ['product' => $product->id, 'page' =>  request()->page, 'perPage' => $perPage ]) }}" class="btn btn-primary btn-sm">Sửa</a>
                        @endcan

                        @can('delete', $product)
                            <form action="{{ route('products.destroy', ['product' => $product->id, 'page' =>  request()->page, 'perPage' => $perPage ]) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table> 
</div> 
 

<div class="d-flex justify-content-between mx-0 mb-3 small mt-3">
    <div class="d-flex align-items-center"></div>
    <div class="ms-auto">
        <div class="">
            <nav>
                <ul class="pagination">
                    <li class="page-item {{ $page == 1 ? 'disabled' : ''}}">
                        <a class="page-link" 
                            href="{{ route('products.index', [
                                'page' => $page > 1 ? $page - 1 : 1,
                                'perPage' => $perPage,
                                'keyword' => request()->keyword
                            ]) }}">Trang trước</a>
                    </li>
                    @for ($i=1;$i<=$pageCount;$i++)
                        <li class="page-item {{ $page == $i ? 'disabled active' : ''}}">
                            <a class="page-link" href="{{ 
                                route('products.index', [
                                    'page' => $i,
                                    'perPage' => $perPage,
                                    'keyword' => request()->keyword
                                ]) }}">{{ $i }}</a>
                        </li>
                    @endfor
                        
                    <li class="page-item {{ $page == $pageCount ? 'disabled' : '' }}">
                        <a class="page-link" 
                        href="{{ route('products.index', [
                            'page' => $page < $pageCount ? $page + 1 : $pageCount,
                            'perPage' => $perPage,
                            'keyword' => request()->keyword
                        ]) }}">Trang sau</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>


 



    </div>
</div>





<script> 
 
    $(function() {  
        productList.getDeleteCampaignsButtons().forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                let url = button.getAttribute('href');
                productList.deleteCampaign(url);
            });
        });        
    });

    var productList = {
        init: function() {
            // events
            this.events();
        },
        getDeleteCampaignsButtons() {
            return ProductIndex.productList.getContent().querySelectorAll('[list-action="delete-product"]');
        },
        deleteCampaign(url) { 

            new Dialog('confirm', {
                message: "{{ trans('products.delete._confirm') }}",
                ok: function() {
                    ProductIndex.productList.addLoadingEffect();
                    // load list via ajax
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data:{
                            _method: 'delete' ,
                            _token: CSRF_TOKEN,  
                        },
                    }).done(function(response) {
                        notify({
                            type: response.status,
                            message: response.message,
                        });
                        // load list
                        ProductIndex.productList.load();
                    }).fail(function(jqXHR, textStatus, errorThrown){
                    }).always(function() {
                    });
                }
            })
        },
    }
</script>

@endsection