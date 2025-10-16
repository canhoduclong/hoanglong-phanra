<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\Customer;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Enums\DeliveryStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;

class OrderController extends Controller
{
    public function createNewOrderForm(Request $request)
    {
        
        $variantId = $request->input('variant_id');
        
        if (!$variantId) {
            return redirect()->route('orders.index')->with('error', 'No variant ID provided.');
        }

        
        $variant = ProductVariant::with(['product', 'media'])->find($variantId);

        if (!$variant) {
            return redirect()->route('orders.index')->with('error', 'Variant not found.');
        }
        $customers = Customer::paginate(10);

        return view('orders.create_new', compact('variant', 'customers'));
    }

    public function storeNewOrder(Request $request, OrderService $orderService)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'customer_id' => 'required|exists:customers,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::find($request->input('variant_id'));
        $quantity = $request->input('quantity');

        if ($variant->stock < $quantity) {
            return back()->with('error', 'Not enough stock for this variant.');
        }

        $order = $orderService->createOrder([
            'customer_id' => $request->input('customer_id'),
            'user_id' => auth()->id(),
            'status' => OrderStatus::Pending->value,
            'payment_status' => PaymentStatus::Unpaid->value,
            'delivery_status' => DeliveryStatus::NotShipped->value,
            'total_amount' => 0, // Will be calculated by the service
        ], [['id' => $variant->id, 'quantity' => $quantity]]);

        return redirect()->route('orders.show', $order)->with('success', 'Order created successfully.');
    }
    public function test(Request $request)
    {
        echo "oks";
    }
    public function index(Request $request)
    {
        $query = Order::with('customer', 'user');

        // Filtering
        if ($request->filled('customer_name')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('customer_name') . '%');
            });
        }

        if ($request->filled('phone_number')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('phone', 'like', '%' . $request->input('phone_number') . '%');
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }

        
        // Statistics
        $statsQuery = clone $query;
        $totalInvoiceAmount = $statsQuery->sum('total');
        $totalPaidAmount = $statsQuery->sum('amount_paid');
        $totalOutstandingAmount = $totalInvoiceAmount - $totalPaidAmount;
        $fullyPaidOrders = (clone $statsQuery)->where('payment_status', 'paid')->count();
        $unpaidOrders = (clone $statsQuery)->where('payment_status', 'unpaid')->count();
        $partiallyPaidOrders = (clone $statsQuery)->where('payment_status', 'partially_paid')->count();

        $orders = $query->latest()->paginate(15);

        $users = \App\Models\User::all();
        $statusOptions = collect(OrderStatus::cases())->mapWithKeys(function ($case) {
            return [$case->value => $case->name];
        });

        return view('orders.index', compact(
            'orders',
            'users',
            'statusOptions',
            'totalInvoiceAmount',
            'totalPaidAmount',
            'totalOutstandingAmount',
            'fullyPaidOrders',
            'unpaidOrders',
            'partiallyPaidOrders'
        ));
    }

    public function show(Order $order)
    {
        $order->load('items.variant.product', 'customer');
        return view('orders.show', compact('order'));
    }
}
