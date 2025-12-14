<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::with('images', 'category')
            ->paginate(12);
        $categories = Category::with('products')->get();
        
        return view('shop.index', compact('products', 'categories'));
    }

    public function category(Category $category)
    {
        $products = Product::where('category_id', $category->id)
            ->with('images', 'category')
            ->paginate(12);
        $categories = Category::with('products')->get();
        
        return view('shop.category', compact('products', 'categories', 'category'));
    }

    public function show(Product $product)
    {
        $product->load('images', 'variations', 'category');
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('images')
            ->take(4)
            ->get();
        
        return view('shop.product-detail', compact('product', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $products = Product::with('images', 'category')
            ->where('name', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->paginate(12);
        
        $categories = Category::with('products')->get();
        
        return view('shop.search-results', compact('products', 'categories', 'query'));
    }
}
