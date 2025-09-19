

<?php

// AJAX popup chọn khách hàng
Route::get('customers/popup/search', [App\Http\Controllers\CustomerPopupController::class, 'search'])->name('customers.popup.search')->middleware('auth');
Route::post('customers/popup/store', [App\Http\Controllers\CustomerPopupController::class, 'store'])->name('customers.popup.store')->middleware('auth');


use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantPriceController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\CategoryController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerTypeController;
use App\Http\Controllers\CustomerAddressController;
use App\Http\Controllers\PermissionAddressController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AIController;


Route::get('/', function () {
    return view('welcome');
});

// Auth pages
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1'); // 5 lần/phút chống brute-force
});

Route::middleware(['auth'])->group(function () {
    // AJAX lấy tổng tiền đơn hàng
    Route::get('orders/ajax/total', [App\Http\Controllers\OrderAjaxController::class, 'total'])->name('orders.ajax.total');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý sản phẩm
    Route::resource('products', ProductController::class)->middleware('checkpermission');
    // Quản trị biến thể sản phẩm
    Route::resource('product-variants', ProductVariantController::class)->only(['index', 'create', 'store', 'edit', 'update']);


    Route::get('variants/{variant}/edit-price', [ProductVariantPriceController::class, 'edit'])->name('variants.edit-price');
    Route::put('variants/{variant}/update-price', [ProductVariantPriceController::class, 'update'])->name('variants.update-price');

    // Lịch sử giá (AJAX)
    Route::get('variants/{id}/price-history', [ProductVariantPriceController::class, 'priceHistory'])->name('variants.price-history');
    // Cập nhật giá mới (AJAX)
    Route::post('variants/{id}/update-price', [ProductVariantPriceController::class, 'updatePrice'])->name('variants.update-price');

    // Popup gallery chọn ảnh cho biến thể
    Route::get('variants/image-library', [MediaController::class, 'variantImageLibrary'])->name('variants.image-library');
    
    
    Route::post('/ai/generate-description', [AIController::class, 'generateDescription'])->name('ai.generateDescription');


    // Quản lý đơn hàng
    Route::get('orders/list-ajax', [OrderController::class, 'listAjax'])->name('orders.list-ajax');
    Route::get('orders/{order}/list-variant', [OrderController::class, 'listVariant'])->name('orders.list-variant');
    Route::get('orders/{order}/variants-list', [OrderController::class, 'variantsList'])->name('orders.variants-list');
    Route::post('orders/{order}/toggle-status', [OrderController::class, 'toggleStatus']);
    Route::resource('orders', OrderController::class)->middleware('checkpermission');

    Route::post('orders/{order}/add-variant', [OrderController::class, 'addVariant']);
    Route::post('orders/{order}/remove-variant', [OrderController::class, 'removeVariant']);


    // Quản lý danh mục
    Route::resource('categories', CategoryController::class)->middleware('checkpermission');

    // Quản lý vai trò
    Route::resource('roles', RoleController::class)->middleware('checkpermission'); 

    // Quản lý quyền
    //Route::resource('permissions', PermissionController::class);//->middleware('checkpermission');

    Route::resource('permissions', PermissionController::class)->middleware('checkpermission'); 


    // Quản lý người dùng
    Route::resource('users', UserController::class)->middleware('checkpermission');

    // Ví dụ: khi sau này bạn thêm Customer
    Route::get('customers/export', [CustomerController::class, 'export'])->name('customers.export')->middleware('checkpermission');
    Route::get('customers/import', [CustomerController::class, 'importForm'])->name('customers.import.form')->middleware('checkpermission');
    Route::post('customers/import', [CustomerController::class, 'import'])->name('customers.import')->middleware('checkpermission');
    Route::resource('customers', CustomerController::class)->middleware('checkpermission');
    // Xóa nhiều khách hàng
    Route::post('customers/bulk-delete', [CustomerController::class, 'bulkDelete'])->name('customers.bulkDelete')->middleware('checkpermission');
    
    Route::resource('customertype', CustomerTypeController::class)->middleware('checkpermission');


    // Route list toàn bộ địa chỉ (không cần customerId)
    Route::get('customers/list/addresses', [CustomerAddressController::class, 'list'])
    ->name('customers.addresses.list')->middleware('checkpermission');
    //->middleware('permission:addresses.view'); // nếu bạn dùng middleware permission

    //Route::resource('customeraddress', CustomerAddressController::class)->middleware('checkpermission');
    Route::resource('customers.addresses', CustomerAddressController::class)->middleware('checkpermission');
    
    // Media 
    //Route::resource('media', MediaController::class);
    Route::resource('media', MediaController::class)->parameters([
        'media' => 'media'
    ]);
    
    Route::get('/media/library/popup', [MediaController::class, 'popup'])->name('media.library.popup');
    Route::post('/media/popup/store', [MediaController::class, 'popupStore'])->name('media.popup.store');

    Route::get('/media/gallery/popup', [MediaController::class, 'popupGallery'])->name('media.gallery.popup');
    Route::post('/media/gallery/store', [MediaController::class, 'storeGallery'])->name('media.gallery.store');

    

    // Route::get('{type}/{id}/media', [MediaController::class, 'index'])->name('media.index');
    // Route::post('{type}/{id}/media', [MediaController::class, 'store'])->name('media.store');
    // Route::get('media/{media}', [MediaController::class, 'show'])->name('media.show');
    // Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');


    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); 

});


//  Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
//  Route::resource('products', ProductController::class)->middleware('auth');
/*
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/manager/dashboard', [ManagerController::class, 'index'])->name('manager.dashboard');
    Route::get('/staff/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
});
*/
 


/*
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
*/

// Quản lý giao dịch
Route::resource('transactions', App\Http\Controllers\TransactionController::class)->only(['index','create','store'])->middleware('checkpermission');

