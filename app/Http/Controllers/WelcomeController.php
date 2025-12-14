<?php

namespace App\Http\Controllers;

use App\Models\Product;

class WelcomeController extends Controller
{
    public function index()
    {
        $recentProducts = Product::orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        $featuredProducts = Product::where('is_featured', true)
            ->orderBy('display_order')
            ->limit(8)
            ->get();

        return view('welcome', [
            'recentProducts' => $recentProducts,
            'featuredProducts' => $featuredProducts,
        ]);
    }
}
