<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'images')->paginate(15);
        $categories = Category::orderBy('name')->get();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $variationTypes = ['size', 'color', 'weight', 'volume', 'quantity'];
        return view('admin.products.create', compact('categories', 'variationTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_featured' => 'boolean',
            'image_urls.*' => 'nullable|url',
            'image_files.*' => 'nullable|image|max:2048',
            'variants.*.type' => 'nullable|in:size,color,quantity',
            'variants.*.value' => 'nullable|string',
            'variants.*.price' => 'nullable|numeric|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured'] = $request->has('is_featured');
        $product = Product::create($validated);

        // Handle image URLs and file uploads
        $imageUrls = $request->input('image_urls', []);
        $imageFiles = $request->file('image_files', []);
        
        $index = 0;
        foreach ($imageUrls as $i => $url) {
            if (!empty($url)) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $url,
                    'display_order' => $index++,
                ]);
            }
        }
        
        foreach ($imageFiles as $file) {
            if ($file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => Storage::url($path),
                    'display_order' => $index++,
                ]);
            }
        }

        // Handle variants
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (!empty($variantData['type']) && !empty($variantData['value'])) {
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'type' => $variantData['type'],
                        'value' => $variantData['value'],
                        'additional_price' => $variantData['price'] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'تم إنشاء المنتج بنجاح');
    }

    public function edit(Product $product)
    {
        if (request()->wantsJson()) {
            return response()->json($product->load(['images', 'variations']));
        }
        $categories = Category::orderBy('name')->get();
        $variationTypes = ['size', 'color', 'weight', 'volume', 'quantity'];
        return view('admin.products.edit', compact('product', 'categories', 'variationTypes'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_featured' => 'boolean',
            'image_urls.*' => 'nullable|url',
            'image_files' => 'nullable|array',
            'image_files.*' => 'nullable|image|max:2048',
            'variants.*.type' => 'nullable|in:size,color,quantity',
            'variants.*.value' => 'nullable|string',
            'variants.*.price' => 'nullable|numeric|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured'] = $request->has('is_featured');
        $product->update($validated);

        // Handle images - delete old ones and add new ones
        $product->images()->delete();
        
        $imageUrls = $request->input('image_urls', []);
        $imageFiles = $request->file('image_files', []);
        
        $index = 0;
        foreach ($imageUrls as $i => $url) {
            if (!empty($url)) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $url,
                    'display_order' => $index++,
                ]);
            }
        }
        
        foreach ($imageFiles as $file) {
            if ($file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => Storage::url($path),
                    'display_order' => $index++,
                ]);
            }
        }

        // Handle variants - delete old ones and add new ones
        $product->variations()->delete();
        
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (!empty($variantData['type']) && !empty($variantData['value'])) {
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'type' => $variantData['type'],
                        'value' => $variantData['value'],
                        'additional_price' => $variantData['price'] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            if ($image->image) {
                Storage::disk('public')->delete($image->image);
            }
        }
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }

    public function deleteImage(ProductImage $image)
    {
        if ($image->image) {
            Storage::disk('public')->delete($image->image);
        }
        $image->delete();

        return back()->with('success', 'تم حذف الصورة بنجاح');
    }
}
