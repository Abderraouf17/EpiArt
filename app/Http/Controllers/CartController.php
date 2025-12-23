<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingRule;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function view()
    {
        $cart = session()->get('cart', []);
        return view('shop.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        // No auth check - allow guests to add to cart
        $product = Product::with('images')->find($request->product_id);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'المنتج غير موجود'], 404);
        }

        $cart = session()->get('cart', []);

        $key = $request->product_id . '_' . ($request->variation_value ?? '');

        $cartItem = [
            'id' => $key,
            'product_id' => $request->product_id,
            'name' => $product->name,
            'image' => $product->images->first()?->image_url ?? '',
            'quantity' => $request->quantity ?? 1,
            'variation_type' => $request->variation_type,
            'variation_value' => $request->variation_value,
            'price' => $request->price,
            'slug' => $product->slug,
        ];

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $cartItem['quantity'];
            $cartItem['quantity'] = $cart[$key]['quantity'];
        } else {
            $cart[$key] = $cartItem;
        }

        session()->put('cart', $cart);

        // Save to database if user is authenticated
        if (Auth::check()) {
            $existingItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->where('variation_value', $request->variation_value)
                ->first();

            if ($existingItem) {
                $existingItem->increment('quantity', $request->quantity ?? 1);
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity ?? 1,
                    'variation_type' => $request->variation_type,
                    'variation_value' => $request->variation_value,
                    'price' => $request->price,
                ]);
            }
        }

        $totalQuantity = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'status' => 'success',
            'message' => 'تمت إضافة المنتج إلى السلة',
            'cartCount' => count($cart),
            'totalQuantity' => $totalQuantity,
            'item' => $cartItem
        ]);
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        unset($cart[$request->key]);
        session()->put('cart', $cart);

        if (Auth::check()) {
            // The key is built as: $product_id . '_' . ($variation_value ?? '')
            $parts = explode('_', $request->key, 2);
            $pId = $parts[0];
            $vVal = $parts[1] ?? null;
            if ($vVal === '')
                $vVal = null;

            CartItem::where('user_id', Auth::id())
                ->where('product_id', $pId)
                ->where('variation_value', $vVal)
                ->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'تم إزالة المنتج من السلة'
        ]);
    }

    public function clear()
    {
        // Clear session cart
        session()->forget('cart');

        // Clear database cart for authenticated users
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'تم إفراغ السلة'
        ]);
    }

    public function checkout()
    {
        // Allow guests to checkout
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/shop')->with('error', 'السلة فارغة');
        }

        $shippingRules = ShippingRule::orderBy('wilaya')->get();

        return view('shop.checkout', compact('cart', 'shippingRules'));
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'delivery_type' => 'required|in:home,desk',
            'wilaya' => 'required|string',
            'address' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'السلة فارغة');
        }

        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $deliveryFee = ShippingRule::getFeeByWilayaAndType(
            $request->wilaya,
            $request->delivery_type
        );

        $totalPrice += $deliveryFee;

        $order = Order::create([
            'user_id' => Auth::id(), // Will be null for guests
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'delivery_type' => $validated['delivery_type'],
            'wilaya' => $validated['wilaya'],
            'address' => $validated['address'],
            'total_price' => $totalPrice,
            'delivery_fee' => $deliveryFee,
            'status' => 'pending',
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'variation_type' => $item['variation_type'],
                'variation_value' => $item['variation_value'],
            ]);
        }

        session()->forget('cart');

        // Clear database cart for authenticated users
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        }

        // Redirect based on authentication status
        if (Auth::check()) {
            return redirect()->route('dashboard')
                ->with('success', 'تم إنشاء الطلب بنجاح. رقم الطلب: ' . $order->id);
        } else {
            return redirect()->route('order.success', $order->id);
        }
    }

    public function syncCart()
    {
        if (!Auth::check()) {
            return;
        }

        // Get session cart
        $sessionCart = session()->get('cart', []);

        // Get database cart
        $dbCartItems = CartItem::where('user_id', Auth::id())
            ->with('product.images')
            ->get();

        // Convert database cart to session format
        $dbCart = [];
        foreach ($dbCartItems as $item) {
            $key = $item->product_id . '_' . ($item->variation_value ?? '');
            $dbCart[$key] = [
                'id' => $key,
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'image' => $item->product->images->first()?->image_url ?? '',
                'quantity' => $item->quantity,
                'variation_type' => $item->variation_type,
                'variation_value' => $item->variation_value,
                'price' => $item->price,
                'slug' => $item->product->slug,
            ];
        }

        // Merge session cart with database cart
        foreach ($sessionCart as $key => $item) {
            if (isset($dbCart[$key])) {
                // Item exists in both - add quantities
                $dbCart[$key]['quantity'] += $item['quantity'];

                // Update database
                CartItem::where('user_id', Auth::id())
                    ->where('product_id', $item['product_id'])
                    ->where('variation_value', $item['variation_value'])
                    ->increment('quantity', $item['quantity']);
            } else {
                // Item only in session - add to database
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'variation_type' => $item['variation_type'],
                    'variation_value' => $item['variation_value'],
                    'price' => $item['price'],
                ]);

                $dbCart[$key] = $item;
            }
        }

        // Update session with merged cart
        session()->put('cart', $dbCart);
    }

    public function orderSuccess(Order $order)
    {
        // Show order success page for guests
        return view('shop.order-success', compact('order'));
    }
}
