<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // AJAX: thêm biến thể vào đơn hàng
    public function addVariant(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $variantId = $request->input('variant_id');
        $variant = ProductVariant::findOrFail($variantId);
        // Nếu đã có thì tăng số lượng, chưa có thì thêm mới
        $item = $order->items()->where('product_variant_id', $variantId)->first();
        if ($item) {
            $item->quantity += 1;
            $item->save();
        } else {
            $order->items()->create([
                'product_id' => $variant->product_id,
                'product_variant_id' => $variantId,
                'quantity' => 1,
                'price' => $variant->price ?? 0,
                'total' => $variant->price ?? 0,
            ]);
        }
        return response()->json(['success' => true]);
    }

    // AJAX: xóa biến thể khỏi đơn hàng
    public function removeVariant(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $variantId = $request->input('variant_id');
        $order->items()->where('product_variant_id', $variantId)->delete();
        return response()->json(['success' => true]);
    }

    // AJAX: trả về danh sách biến thể (order items) cho edit
    public function listVariant(Request $request, $orderId)
    {
        $order = Order::with(['items.variant.product'])->findOrFail($orderId);
        $items = $order->items;
        $total = $items->sum(function($item) { return $item->quantity * $item->price; });
        return view('orders.list_variant', compact('items', 'total'))->render();
    }
    // AJAX: trả về danh sách đơn hàng dạng bảng cho index
    public function listAjax()
    {
        $orders = Order::with(['customer', 'user'])->latest()->get();
        return view('orders.list_table', compact('orders'))->render();
    }

    // AJAX: chuyển trạng thái đơn hàng (toggle demo: chuyển sang trạng thái tiếp theo)
    public function toggleStatus($id)
    {
        $order = Order::findOrFail($id);
        $statuses = array_keys(Order::statusOptions());
        $currentIdx = array_search($order->status, $statuses);
        $nextIdx = ($currentIdx === false || $currentIdx === count($statuses) - 1) ? 0 : $currentIdx + 1;
        $order->status = $statuses[$nextIdx];
        $order->save();
        return response()->json(['status' => $order->status]);
    }
    // AJAX: trả về danh sách biến thể theo id (dùng cho JS quản lý đơn hàng)
    public function variantsList(Request $request, $orderId = null)
    {
        $variantIds = $request->input('ids', []);
        $variants = [];
        $total = 0;
        if (!empty($variantIds)) {
            $variants = ProductVariant::with('product')->whereIn('id', $variantIds)->get();
            foreach ($variants as $v) {
                $total += $v->price ?? 0;
            }
        }
        return view('orders.list', compact('variants', 'total'));
    }
    public function index()
    {
        $orders = Order::with('customer', 'user')->latest()->paginate(20);
        return view('orders.index', compact('orders'));
    }
    public function show($id)
    {
        $order = Order::with(['customer', 'user', 'items.product', 'items.variant'])->findOrFail($id);
        return view('orders.show', compact('order'));
    }
    public function create()
    {
    $customers = Customer::all();
    $users = User::all();
    $products = Product::with('variants')->get();
    $statusOptions = \App\Models\Order::statusOptions();
    return view('orders.create', compact('customers', 'users', 'products', 'statusOptions'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'nullable|exists:users,id',
            'code' => 'required|unique:orders,code',
            'status' => 'required',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'amount_due' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'payment_status' => 'nullable|string|max:20',
        ]);
        $order = Order::create([
            'customer_id' => $data['customer_id'],
            'user_id' => $data['user_id'],
            'code' => $data['code'],
            'status' => $data['status'],
            'total' => $data['total'],
            'amount_paid' => $data['amount_paid'] ?? 0,
            'amount_due' => $data['amount_due'] ?? (($data['total'] ?? 0) - ($data['amount_paid'] ?? 0)),
            'payment_method' => $data['payment_method'] ?? null,
            'payment_status' => $data['payment_status'] ?? 'unpaid',
        ]);
        $total = 0;
        foreach ($data['items'] as $item) {
            $itemTotal = $item['quantity'] * $item['price'];
            $order->items()->create([
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['product_variant_id'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $itemTotal,
            ]);
            $total += $itemTotal;
        }
        $order->update(['total' => $total]);
        return redirect()->route('orders.index')->with('success', 'Tạo đơn hàng thành công!');
    }
    public function edit($id)
    {
    $order = Order::with(['items'])->findOrFail($id);
    $customers = Customer::all();
    $users = User::all();
    $products = Product::with('variants')->get();
    $statusOptions = \App\Models\Order::statusOptions();
    return view('orders.edit', compact('order', 'customers', 'users', 'products', 'statusOptions'));
    }
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'required',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'amount_due' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'payment_status' => 'nullable|string|max:20',
        ]);
        $order->update([
            'customer_id' => $data['customer_id'],
            'user_id' => $data['user_id'],
            'status' => $data['status'],
            'total' => $data['total'],
            'amount_paid' => $data['amount_paid'] ?? 0,
            'amount_due' => $data['amount_due'] ?? (($data['total'] ?? 0) - ($data['amount_paid'] ?? 0)),
            'payment_method' => $data['payment_method'] ?? null,
            'payment_status' => $data['payment_status'] ?? 'unpaid',
        ]);
        $order->items()->delete();
        $total = 0;
        foreach ($data['items'] as $item) {
            $itemTotal = $item['quantity'] * $item['price'];
            $order->items()->create([
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['product_variant_id'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $itemTotal,
            ]);
            $total += $itemTotal;
        }
        $order->update(['total' => $total]);
        return redirect()->route('orders.index')->with('success', 'Cập nhật đơn hàng thành công!');
    }
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->items()->delete();
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Đã xóa đơn hàng!');
    }
}
