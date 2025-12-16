<?php

namespace App\Http\Controllers;

use App\Models\Product;

class WelcomeController extends Controller
{
    public function index()
    {
        // Fetch products marked as "new" (both spice and beauty) for tab-based filtering
        $newProducts = Product::with('images')
            ->where('is_new', true)
            ->orderBy('display_order')
            ->limit(8) // 4 spice + 4 beauty
            ->get();

        $featuredProducts = Product::with('images')
            ->where('is_featured', true)
            ->orderBy('display_order')
            ->limit(8) // 4 spice + 4 beauty
            ->get();

        return view('welcome', [
            'newProducts' => $newProducts,
            'featuredProducts' => $featuredProducts,
        ]);
    }
}
