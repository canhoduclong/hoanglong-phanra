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
								<div class="fs-sm text-white opacity-75 mb-1">Customer</div>
								<div class="fw-semibold">Royal Dutch Shell</div>
							</div>
						</div>
					</a>

					<div class="dropdown-menu w-100">
						<a href="#" class="dropdown-item hstack gap-2 py-2">
							<img src="assets/images/brands/tesla.svg" class="w-32px h-32px" alt="">
							<div>
								<div class="fw-semibold">Tesla Motors Inc</div>
								<div class="fs-sm text-muted">42 users</div>
							</div>
						</a>
						 
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


					</ul>
				</div>
				<!-- /main navigation -->

			</div>
			<!-- /sidebar content -->