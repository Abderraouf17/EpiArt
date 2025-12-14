<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $orders = $user->orders()->with('items.product')->latest()->get();
        $activeOrders = $user->orders()
            ->whereIn('status', ['pending', 'confirmed', 'shipped'])
            ->latest()
            ->get();
        $wishlistProducts = $user->wishlists()
            ->with('product.images')
            ->get()
            ->pluck('product');

        return view('dashboard', compact('orders', 'activeOrders', 'wishlistProducts'));
    }

    public function addToWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'المنتج موجود بالفعل في قائمتك المفضلة'
            ]);
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'تمت إضافة المنتج إلى قائمتك المفضلة'
        ]);
    }

    public function removeFromWishlist(Request $request)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->delete();

        return back()->with('success', 'تم إزالة المنتج من قائمتك المفضلة');
    }
}
