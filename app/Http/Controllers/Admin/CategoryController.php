<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('featuredProduct')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('admin.categories.create', compact('products'));
    }

    public function store(Request $request)
    {
        // Handle image - prioritize URL over file upload
        $imageUrl = null;
        $imageUrls = $request->input('category_image_urls', []);
        $imageFiles = $request->file('category_image_files', []);
        
        if ($request->hasFile('image')) {
            $imageFiles[] = $request->file('image');
        }
        
        // Use first non-empty URL
        foreach ($imageUrls as $url) {
            if (!empty($url)) {
                $imageUrl = $url;
                break;
            }
        }
        
        // If no URL, use first file upload
        if (!$imageUrl && count($imageFiles) > 0) {
            foreach ($imageFiles as $file) {
                if ($file) {
                    $path = $file->store('categories', 'public');
                    $imageUrl = Storage::url($path);
                    $validated['image'] = $path;
                    break;
                }
            }
        }
        
        if ($imageUrl) {
            $validated['image_url'] = $imageUrl;
        }

        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم إنشاء الفئة بنجاح');
    }

    public function edit(Category $category)
    {
        if (request()->wantsJson()) {
            return response()->json($category);
        }
        $products = Product::orderBy('name')->get();
        return view('admin.categories.edit', compact('category', 'products'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'number' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'category_image_urls.*' => 'nullable|url',
            'category_image_files.*' => 'nullable|image|max:2048',
            'featured_product_id' => 'nullable|exists:products,id',
        ]);

        // Handle image - prioritize URL over file upload
        $imageUrl = null;
        $imageUrls = $request->input('category_image_urls', []);
        $imageFiles = $request->file('category_image_files', []);
        
        if ($request->hasFile('image')) {
            $imageFiles[] = $request->file('image');
        }
        
        // Use first non-empty URL
        foreach ($imageUrls as $url) {
            if (!empty($url)) {
                $imageUrl = $url;
                break;
            }
        }
        
        // If no URL, use first file upload
        if (!$imageUrl && count($imageFiles) > 0) {
            foreach ($imageFiles as $file) {
                if ($file) {
                    // Delete old image if exists
                    if ($category->image) {
                        Storage::disk('public')->delete($category->image);
                    }
                    $path = $file->store('categories', 'public');
                    $imageUrl = Storage::url($path);
                    $validated['image'] = $path;
                    break;
                }
            }
        }
        
        if ($imageUrl) {
            $validated['image_url'] = $imageUrl;
        }

        $validated['slug'] = Str::slug($validated['name']);
        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم تحديث الفئة بنجاح');
    }

    public function destroy(Category $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم حذف الفئة بنجاح');
    }
}
