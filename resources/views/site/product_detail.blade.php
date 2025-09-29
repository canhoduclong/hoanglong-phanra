@extends('layouts.site')

@section('content')
<div class="container product-detail-container">
    <div class="row">
        <!-- Image Gallery -->
        <div class="col-md-6">
            <div id="main-image-container">
                <img src="" class="img-fluid" alt="Product Image">
            </div>
            <div id="thumbnail-gallery" class="mt-2">
                <!-- Thumbnails will be injected by JS -->
            </div>
        </div>

        <!-- Product Info & Variant Selection -->
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p class="text-muted">{{ $product->description }}</p>
            <hr>

            <div id="variant-selection">
                @foreach($attributes as $name => $values)
                    <div class="form-group mb-3">
                        <label for="attribute-{{ Str::slug($name) }}" class="form-label"><strong>{{ $name }}</strong></label>
                        <select class="form-select variant-selector" id="attribute-{{ Str::slug($name) }}" data-attribute-name="{{ $name }}">
                            <option value="">Choose {{ $name }}</option>
                            @foreach($values as $value)
                                <option value="{{ $value->id }}">{{ $value->value }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>

            <button id="reset-selection-btn" class="btn btn-sm btn-secondary mb-3">Reset Selection</button>

            <div id="selected-variant-info" class="d-none">
                <h4 id="variant-price"></h4>
                <p id="variant-description"></p>
            </div>

            <div id="add-to-cart-section" class="d-none">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" id="quantity" class="form-control" value="1" min="1">
                    </div>
                    <div class="col-md-8">
                        <button id="add-to-cart-btn" class="btn btn-primary w-100" disabled>Add to Cart</button>
                    </div>
                </div>
                 <p class="mt-2">Total: <strong id="total-price"></strong></p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    #thumbnail-gallery .thumbnail {
        width: 80px;
        height: 80px;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid transparent;
        margin-right: 10px;
        transition: border-color 0.3s;
    }
    #thumbnail-gallery .thumbnail.active {
        border-color: #007bff;
    }
    #main-image-container img {
        max-height: 500px;
        width: 100%;
        object-fit: contain;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productData = @json($product);

        const productManager = {
            // DOM Elements
            elements: {
                mainImage: document.querySelector('#main-image-container img'),
                thumbnailGallery: document.querySelector('#thumbnail-gallery'),
                variantSelectors: document.querySelectorAll('.variant-selector'),
                resetBtn: document.getElementById('reset-selection-btn'),
                variantInfo: document.getElementById('selected-variant-info'),
                variantPrice: document.getElementById('variant-price'),
                variantDescription: document.getElementById('variant-description'),
                addToCartSection: document.getElementById('add-to-cart-section'),
                quantityInput: document.getElementById('quantity'),
                addToCartBtn: document.getElementById('add-to-cart-btn'),
                totalPriceDisplay: document.getElementById('total-price'),
            },

            // State
            state: {
                selectedVariant: null,
                selectedAttributes: {},
                initialImages: productData.gallery.map(g => g.media),
            },

            init() {
                this.renderGallery(this.state.initialImages);
                this.addEventListeners();
            },

            addEventListeners() {
                this.elements.variantSelectors.forEach(selector => {
                    selector.addEventListener('change', this.handleVariantSelection.bind(this));
                });
                this.elements.resetBtn.addEventListener('click', this.resetSelections.bind(this));
                this.elements.quantityInput.addEventListener('input', this.updateTotal.bind(this));
                this.elements.thumbnailGallery.addEventListener('click', this.handleThumbnailClick.bind(this));
            },

            renderGallery(images) {
                this.elements.thumbnailGallery.innerHTML = '';
                if (!images || images.length === 0) {
                    this.elements.mainImage.src = 'https://via.placeholder.com/500x500';
                    return;
                }

                images.forEach((image, index) => {
                    const thumb = document.createElement('img');
                    thumb.src = `/storage/${image.file_path}`;
                    thumb.classList.add('thumbnail');
                    if (index === 0) {
                        thumb.classList.add('active');
                        this.elements.mainImage.src = `/storage/${image.file_path}`;
                    }
                    this.elements.thumbnailGallery.appendChild(thumb);
                });
            },

            handleThumbnailClick(event) {
                if (event.target.classList.contains('thumbnail')) {
                    this.elements.mainImage.src = event.target.src;
                    document.querySelectorAll('#thumbnail-gallery .thumbnail').forEach(t => t.classList.remove('active'));
                    event.target.classList.add('active');
                }
            },

            handleVariantSelection() {
                this.state.selectedAttributes = {};
                this.elements.variantSelectors.forEach(selector => {
                    if (selector.value) {
                        this.state.selectedAttributes[selector.dataset.attributeName] = parseInt(selector.value, 10);
                    }
                });

                const selectedValueIds = Object.values(this.state.selectedAttributes);
                if (selectedValueIds.length < this.elements.variantSelectors.length) {
                    this.clearVariantState();
                    return; // Not all attributes selected yet
                }

                const foundVariant = productData.variants.find(variant => {
                    const variantValueIds = variant.values.map(v => v.id);
                    return selectedValueIds.every(id => variantValueIds.includes(id));
                });

                if (foundVariant) {
                    this.state.selectedVariant = foundVariant;
                    this.updateUIForSelectedVariant();
                } else {
                    this.clearVariantState();
                }
            },

            updateUIForSelectedVariant() {
                const variant = this.state.selectedVariant;
                if (!variant) return;

                // Update price and description
                const price = variant.latest_price_rule ? variant.latest_price_rule.price : 0;
                this.elements.variantPrice.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
                this.elements.variantDescription.textContent = variant.description || ''; // Assuming variants might have descriptions
                this.elements.variantInfo.classList.remove('d-none');

                // Update gallery
                const variantImages = variant.media ? [variant.media] : [];
                this.renderGallery(variantImages.length > 0 ? variantImages : this.state.initialImages);

                // Show and manage cart section
                this.elements.addToCartSection.classList.remove('d-none');
                this.elements.quantityInput.value = 1;
                this.elements.quantityInput.max = variant.stock;
                this.elements.addToCartBtn.disabled = false;
                this.updateTotal();
            },

            updateTotal() {
                if (!this.state.selectedVariant) return;
                const quantity = parseInt(this.elements.quantityInput.value, 10);
                const price = this.state.selectedVariant.latest_price_rule ? this.state.selectedVariant.latest_price_rule.price : 0;
                const total = quantity * price;
                this.elements.totalPriceDisplay.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(total);
            },

            clearVariantState() {
                this.state.selectedVariant = null;
                this.elements.variantInfo.classList.add('d-none');
                this.elements.addToCartSection.classList.add('d-none');
                this.elements.addToCartBtn.disabled = true;
            },

            resetSelections() {
                this.elements.variantSelectors.forEach(selector => selector.selectedIndex = 0);
                this.clearVariantState();
                this.renderGallery(this.state.initialImages);
            }
        };

        productManager.init();
    });
</script>
@endpush