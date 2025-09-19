<?php
namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Transaction::class);
        $transactions = Transaction::with(['order', 'customer'])
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('transactions.index', compact('transactions'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Transaction::class);
        $orders = Order::all();
        $customers = Customer::all();
        return view('transactions.create', compact('orders', 'customers'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Transaction::class);
        $data = $request->validate([
            'order_id' => 'nullable|exists:orders,id',
            'customer_id' => 'nullable|exists:customers,id',
            'amount' => 'required|numeric',
            'type' => 'required|string',
            'method' => 'nullable|string|max:50',
            'note' => 'nullable|string|max:255',
        ]);
        Transaction::create($data);
        return redirect()->route('transactions.index')->with('success', 'Giao dịch đã được ghi nhận.');
    }
}
