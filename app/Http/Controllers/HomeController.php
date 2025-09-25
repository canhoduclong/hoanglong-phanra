<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('welcome', compact('settings'));
    }

    public function variants(Request $request)
    {
        $settings = Setting::all()->keyBy('key');
        $query = \App\Models\ProductVariant::query()->where('stock', '>', 0);

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $variants = $query->with('product', 'latestPriceRule')->paginate(10);

        return view('site.variants', compact('variants', 'settings'));
    }
}
