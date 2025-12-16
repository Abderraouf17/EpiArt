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

    public function wishlist()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $products = $user->wishlists()
            ->with('product.images')
            ->get()
            ->pluck('product');

        return view('shop.wishlist', compact('products'));
    }

    public function addToWishlist(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'يجب تسجيل الدخول لإضافة المنتجات إلى قائمة المفضلة',
                'requiresAuth' => true
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'المنتج موجود بالفعل في قائمتك المفضلة',
                'count' => Auth::user()->wishlists()->count()
            ]);
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'تمت إضافة المنتج إلى قائمتك المفضلة',
            'count' => Auth::user()->wishlists()->count()
        ]);
    }

    public function removeFromWishlist(Request $request)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'تم إزالة المنتج من قائمتك المفضلة',
                'count' => Auth::user()->wishlists()->count()
            ]);
        }

        return back()->with('success', 'تم إزالة المنتج من قائمتك المفضلة');
    }

    public function cancelOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'لا يمكن إلغاء هذا الطلب لأنه قيد التنفيذ أو مكتمل');
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'تم إلغاء الطلب بنجاح');
    }
}
