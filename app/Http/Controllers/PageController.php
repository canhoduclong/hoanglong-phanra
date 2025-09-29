<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Setting;
use App\Models\Page;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $settings = Setting::all()->keyBy('key'); 
        return view('pages.about', compact('settings'));
    }

    public function contact()
    {
        $settings = Setting::all()->keyBy('key'); 
        return view('pages.contact', compact('settings'));
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        Contact::create($request->all());

        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            //'slug' => 'required|unique:pages',
            'content' => 'required',
        ]);

        Page::create($request->all());

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }
    
    public function show(Request $request)
    {
        $slug = $request->slug;
        $page = Page::where('slug', $slug)->firstOrFail();    
        return view('pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:pages,slug,'.$page->id,
            'content' => 'required',
        ]);

        $page->update($request->all());

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }

    public function productsByCategory(Request $request, \App\Models\Category $category = null)
    {
        $settings = Setting::all()->keyBy('key');
        $categories = \App\Models\Category::all();
        $query = \App\Models\ProductVariant::query()->where('stock', '>', 0);

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($category) {
            $query->whereHas('product', function ($q) use ($category) {
                $q->where('category_id', $category->id);
            });
        }

        $variants = $query->with('product', 'latestPriceRule')->paginate(10);

        return view('site.products_by_category', compact('variants', 'settings', 'categories', 'category'));
    }

    public function productList(Request $request, \App\Models\Category $category = null)
    {
        $settings = Setting::all()->keyBy('key');
        $categories = \App\Models\Category::all();
        $query = \App\Models\ProductVariant::query()->where('stock', '>', 0);

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($category) {
            $query->whereHas('product', function ($q) use ($category) {
                $q->where('category_id', $category->id);
            });
        }

        $variants = $query->with('product', 'latestPriceRule')->paginate(10);

        return view('site.product_list', compact('variants', 'settings', 'categories', 'category'));
    }

    public function myDashboard(Request $request)
    {
        $settings = Setting::all()->keyBy('key');
        $user = auth()->user();
        
        $customer = \App\Models\Customer::updateOrCreate(
            ['email' => $user->email],
            ['user_id' => $user->id, 'name' => $user->name]
        );

        return view('site.my_dashboard', compact('settings', 'user', 'customer'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        //$user->load('customer'); // Eager load or refresh the customer relationship
        $customer = $user->customer; 
        $request->validate([
            'name' => 'required|string|max:255', 
            'phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'note' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'dob', 'gender', 'note']);

        if ($customer) {
            $customer->update($data);
        }

        return redirect()->route('pages.my_dashboard')->with('success', 'Profile updated successfully.');
    }

    public function variantDetail(ProductVariant $variant)
    {
        $settings = Setting::all()->keyBy('key');
        $product = $variant->product;
        $other_variants = $product->variants()->where('id', '!=', $variant->id)->get();

        return view('site.variant_detail', compact('variant', 'product', 'other_variants', 'settings'));
    }

    public function myOrders(Request $request)
    {
        $settings = Setting::all()->keyBy('key');
        $user = auth()->user();
        $orders = \App\Models\Order::where('user_id', $user->id)->latest()->paginate(10);

        return view('site.my_orders', compact('settings', 'user', 'orders'));
    }
}