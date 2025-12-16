<?php

namespace App\Http\Controllers;

use App\Models\Product;

class WelcomeController extends Controller
{
    public function index()
    {
        $recentProducts = Product::with('images')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        $featuredProducts = Product::with('images')
            ->where('is_featured', true)
            ->orderBy('display_order')
            ->limit(8)
            ->get();

        return view('welcome', [
            'recentProducts' => $recentProducts,
            'featuredProducts' => $featuredProducts,
        ]);
    }

    public function beauty()
    {
        // Get beauty-related categories (you can adjust these names based on your actual categories)
        $beautyCategories = \App\Models\Category::whereIn('slug', [
            'skincare',
            'haircare',
            'aromatherapy',
            'cosmetics',
            'wellness',
            'beauty'
        ])->pluck('id');

        $recentProducts = Product::with('images')
            ->whereIn('category_id', $beautyCategories)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        $featuredProducts = Product::with('images')
            ->whereIn('category_id', $beautyCategories)
            ->where('is_featured', true)
            ->orderBy('display_order')
            ->limit(8)
            ->get();

        return view('beauty', [
            'recentProducts' => $recentProducts,
            'featuredProducts' => $featuredProducts,
        ]);
    }
}
