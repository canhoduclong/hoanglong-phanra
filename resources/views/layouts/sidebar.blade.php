<!-- Sidebar header -->
			<div class="sidebar-section bg-black bg-opacity-10 border-bottom border-bottom-white border-opacity-10">
				<div class="sidebar-logo d-flex justify-content-center align-items-center">
					<a href="index.html" class="d-inline-flex align-items-center py-2">
						<img src="assets/images/logo_icon.svg" class="sidebar-logo-icon" alt="">
						<img src="assets/images/logo_text_light.svg" class="sidebar-resize-hide ms-3" height="14" alt="">
					</a>

					<div class="sidebar-resize-hide ms-auto">
						<button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
							<i class="ph-arrows-left-right"></i>
						</button>

						<button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-mobile-main-toggle d-lg-none">
							<i class="ph-x"></i>
						</button>
					</div>
				</div>
			</div>
			<!-- /sidebar header -->


			<!-- Sidebar content -->
			<div class="sidebar-content">

				<!-- Customers -->
				<div class="sidebar-section sidebar-resize-hide dropdown mx-2">
					<a href="#" class="btn btn-link text-body text-start lh-1 dropdown-toggle p-2 my-1 w-100" data-bs-toggle="dropdown" data-color-theme="dark">
						<div class="hstack gap-2 flex-grow-1 my-1">
							<img src="assets/images/brands/shell.svg" class="w-32px h-32px" alt="">
							<div class="me-auto">
								<div class="fs-sm text-white opacity-75 mb-1">{{ auth()->user()->roles()->first()->name ?? '' }}</div>
								<div class="fw-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
							</div>
						</div>
					</a>

					<div class="dropdown-menu w-100">
						
										<!-- Thông tin User -->
					<div class="p-4 border-b border-gray-700 items-center">
						 
						<div>
							<div class="font-semibold">{{ auth()->user()->name }}</div>
							<div class="text-sm text-gray-400">{{ auth()->user()->email }}</div>
							<div class="text-xs text-gray-500">
								@if(auth()->user()->roles->isNotEmpty())
									{{ auth()->user()->roles->pluck('name')->join(', ') }}
								@else
									No Role
								@endif
							</div>
						</div>

						<form method="POST" action="{{ route('logout') }}">
							@csrf
							<button class="w-full bg-red-600 px-4 py-2 rounded hover:bg-red-700">
								Đăng xuất
							</button>
						</form>

					</div>


						 
					</div>
				</div>
				<!-- /customers --> 

				<!-- Main navigation -->
				<div class="sidebar-section">
					<ul class="nav nav-sidebar" data-nav-type="accordion">

						<!-- Main -->
						<li class="nav-item-header">
							<div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Main</div>
							<i class="ph-dots-three sidebar-resize-show"></i>
						</li>
						<li class="nav-item">
							<a href="{{ route('dashboard') }}" class="nav-link active">
								<i class="ph-house"></i>
								<span>
									Dashboard
									<span class="d-block fw-normal opacity-50">No pending orders</span>
								</span>
							</a>
						</li>

						<li class="nav-item-header">
							<div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Content</div>
							<i class="ph-dots-three sidebar-resize-show"></i>
						</li>

						<li class="nav-item nav-item-submenu">
							<a href="{{ route('admin.posts.index') }}" class="nav-link">
								<i class="ph-blogger"></i>
								<span>Blog</span>
							</a> 
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="{{ route('admin.post-categories.index') }}" class="nav-link"><span>Categories</span></a>
						</li>
						<li class="nav-item">
							<a href="{{ route('admin.pages.index') }}" class="nav-link">
								<i class="ph-file-text"></i>
								<span>Pages</span>
							</a>
						</li>
						<li class="nav-item ">
							<a href="{{ route('permissions.index') }}" class="nav-link">
								<i class="ph-note-blank"></i>
								<span>Quyền - Tính năng</span>
							</a>
						</li>
						
						<li class="nav-item ">
							<a href="{{ route('roles.index') }}" class="nav-link">
								<i class="ph-note-blank"></i>
								<span>Roles - Vai trò</span>
							</a>
						</li>
						
						<li class="nav-item ">
							<a href="{{ route('users.index') }}" class="nav-link">
								<i class="ph-note-blank"></i>
								<span>User</span>
							</a>
						</li>
						<li class="nav-item ">
							<a href="{{ route('products.index') }}" class="nav-link{{ request()->routeIs('products.*') ? ' active' : '' }}">
								<i class="ph-note-blank"></i>
								<span>Product</span>
							</a>
						</li>
						<li class="nav-item ">
							<a href="{{ route('product-variants.index') }}" class="nav-link{{ request()->routeIs('product-variants.*') ? ' active' : '' }}">
								<i class="ph-note-blank"></i>
								<span>Product Variants</span>
							</a>
						</li>
						<li class="nav-item ">
							<a href="{{ route('categories.index') }}" class="nav-link{{ request()->routeIs('categories.*') ? ' active' : '' }}">
								<i class="ph-note-blank"></i>
								<span>Categories</span>
							</a>
						</li>
                       
						 

						<li class="nav-item ">
							<a href="{{ route('customertype.index') }}" class="nav-link{{ request()->routeIs('customertype.*') ? ' active' : '' }}">
								<i class="ph-note-blank"></i>
								<span>Customer Type</span>
							</a>
						</li>
						<li class="nav-item ">
							<a href="{{ route('warehouses.index') }}" class="nav-link{{ request()->routeIs('warehouses.*') ? ' active' : '' }}">
								<i class="ph-storefront"></i>
								<span>Warehouses</span>
							</a>
						</li>
						<li class="nav-item ">
							<a href="{{ route('inventories.index') }}" class="nav-link{{ request()->routeIs('inventories.*') ? ' active' : '' }}">
								<i class="ph-package"></i>
								<span>Inventories</span>
							</a>
						</li>
						<li class="nav-item ">
							<a href="{{ route('inventory-movements.index') }}" class="nav-link{{ request()->routeIs('inventory-movements.*') ? ' active' : '' }}">
								<i class="ph-arrows-left-right"></i>
								<span>Inventory Movements</span>
							</a>
						</li>
						<li class="nav-item ">
							<a href="{{ route('inventory-documents.index') }}" class="nav-link{{ request()->routeIs('inventory-documents.*') ? ' active' : '' }}">
								<i class="ph-files"></i>
								<span>Inventory Documents</span>
							</a>
						</li>
						<li class="nav-item ">
							<a href="{{ route('inventory-adjustments.index') }}" class="nav-link{{ request()->routeIs('inventory-adjustments.*') ? ' active' : '' }}">
								<i class="ph-wrench"></i>
								<span>Inventory Adjustments</span>
							</a>
						</li>
						<li class="nav-item ">
							<a href="{{ route('inventory-reservations.index') }}" class="nav-link{{ request()->routeIs('inventory-reservations.*') ? ' active' : '' }}">
								<i class="ph-timer"></i>
								<span>Inventory Reservations</span>
							</a>
						</li>
						<li class="nav-item ">
							<a href="{{ route('order-returns.index') }}" class="nav-link{{ request()->routeIs('order-returns.*') ? ' active' : '' }}">
								<i class="ph-arrow-u-left-left"></i>
								<span>Order Returns</span>
							</a>
						</li>
						<li class="nav-item ">
							<a href="{{ route('customers.index') }}" class="nav-link{{ request()->routeIs('customers.*') ? ' active' : '' }}">
								<i class="ph-note-blank"></i>
								<span>Customer</span>
							</a>
						</li>
						<li class="nav-item ">
							<a href="{{ route('orders.index') }}" class="nav-link{{ request()->routeIs('orders.*') ? ' active' : '' }}">
								<i class="ph-note-blank"></i>
								<span>Đơn hàng</span>
							</a>
						</li>
						<li class="nav-item ">
							<a href="{{ route('customers.addresses.list' ) }}" class="nav-link">
								<i class="ph-note-blank"></i>
								<span>Customer Address</span>
							</a>
						</li>
						
						<li class="nav-item ">
							<a href="{{ route('media.index' ) }}" class="nav-link{{ request()->routeIs('media.*') ? ' active' : '' }}">
								<i class="ph-note-blank"></i>
								<span>Media</span>
							</a>
						</li>
						<li class="nav-item ">
							<a href="{{ route('transactions.index') }}" class="nav-link{{ request()->routeIs('transactions.*') ? ' active' : '' }}">
								<i class="ph-note-blank"></i>
								<span>Giao dịch</span>
							</a>
						</li>

						<li class="nav-item">
							<a href="{{ route('admin.settings.index') }}" class="nav-link{{ request()->routeIs('admin.settings.index') ? ' active' : '' }}">
								<i class="ph-gear"></i>
								<span>Settings</span>
							</a>
						</li>


					</ul>
					 <!-- Logout -->
					<div class="p-4 border-t border-gray-700">
						
					</div>

				</div>
				<!-- /main navigation -->

			</div>
			<!-- /sidebar content -->