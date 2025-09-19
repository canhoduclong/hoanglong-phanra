<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function edit($id)
    {
        $variant = \App\Models\ProductVariant::findOrFail($id);
        $products = \App\Models\Product::orderBy('name')->get();
        return view('product_variants.edit', compact('variant', 'products'));
    }

    public function update(Request $request, $id)
    {
        $variant = \App\Models\ProductVariant::findOrFail($id);
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'sku' => 'required|string|unique:product_variants,sku,' . $variant->id,
            'size' => 'nullable|string',
            'quality' => 'nullable|string',
            'production_date' => 'nullable|date',
            'stock' => 'nullable|integer',
            'media_id' => 'nullable|integer|exists:media,id',
        ]);
        $variant->update($data);
        // Gán lại media cho biến thể
        if (!empty($data['media_id'])) {
            \App\Models\MediaLink::updateOrCreate(
                [
                    'model_type' => $variant::class,
                    'model_id'   => $variant->id,
                    'role'       => 'variant',
                ],
                [
                    'media_id'   => $data['media_id'],
                ]
            );
        } else {
            \App\Models\MediaLink::where([
                'model_type' => $variant::class,
                'model_id'   => $variant->id,
                'role'       => 'variant',
            ])->delete();
        }
        return redirect()->route('product-variants.index')->with('success', 'Đã cập nhật biến thể thành công!');
    }
    public function index(Request $request)
    {
        $query = ProductVariant::with(['product', 'mediaLink.media']);
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where('sku', 'like', "%$q%")
                  ->orWhere('size', 'like', "%$q%")
                  ->orWhere('quality', 'like', "%$q%")
                  ->orWhereHas('product', function($sub) use ($q) {
                      $sub->where('name', 'like', "%$q%") ;
                  });
        }
        $variants = $query->orderByDesc('id')->paginate(20);
        return view('product_variants.index', compact('variants'));
    }

    public function create()
    {
        $products = \App\Models\Product::orderBy('name')->get();
        return view('product_variants.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'sku' => 'required|string|unique:product_variants,sku',
            'size' => 'nullable|string',
            'quality' => 'nullable|string',
            'production_date' => 'nullable|date',
            'stock' => 'nullable|integer',
            'media_id' => 'nullable|integer|exists:media,id',
            'price' => 'nullable|numeric|min:0',
        ]);
        $variant = ProductVariant::create($data);
        // Gán media nếu có
        if (!empty($data['media_id'])) {
            \App\Models\MediaLink::updateOrCreate([
                'model_type' => $variant::class,
                'model_id'   => $variant->id,
                'role'       => 'variant',
            ], [
                'media_id'   => $data['media_id'],
            ]);
        }
        // Tạo price rule đầu tiên
        $price = $data['price'] ?? null;
        if (!$price) {
            $product = \App\Models\Product::find($data['product_id']);
            $price = $product?->default_price ?? 0;
        }
        $variant->priceRules()->create([
            'price' => $price,
            'start_date' => now(),
            'reason' => 'Giá khởi tạo',
        ]);
        return redirect()->route('product-variants.index')->with('success', 'Đã thêm biến thể thành công!');
    }
}
