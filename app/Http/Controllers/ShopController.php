<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
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

    public function apiSearch(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Get all products for fuzzy matching
        $allProducts = Product::with('images')->get();
        $results = [];

        foreach ($allProducts as $product) {
            // Calculate similarity using Levenshtein distance
            $distance = levenshtein(
                strtolower($query),
                strtolower($product->name)
            );

            // Also check if query is contained in the name
            $contains = stripos($product->name, $query) !== false;

            // Accept if exact match or close match (distance <= 3 for typos)
            if ($contains || $distance <= 3) {
                $results[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => number_format((float) $product->price, 0),
                    'image' => $product->images->first()?->image_url ?? '/images/placeholder.jpg',
                    'slug' => $product->slug,
                    'relevance' => $contains ? 0 : $distance // Lower is better
                ];
            }
        }

        // Sort by relevance (exact matches first, then by distance)
        usort($results, function ($a, $b) {
            return $a['relevance'] - $b['relevance'];
        });

        // Return top 8 results
        return response()->json(array_slice($results, 0, 8));
    }
}
