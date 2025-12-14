<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingRule;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
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
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'يجب أن تكون مسجل الدخول لإضافة عناصر إلى السلة'
            ], 401);
        }

        $product = Product::with('images')->find($request->product_id);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'المنتج غير موجود'], 404);
        }

        $cart = session()->get('cart', []);
        
        $key = $request->product_id . '_' . ($request->variation_value ?? '');
        
        $cartItem = [
            'id' => $key, // Use composite key for frontend
            'product_id' => $request->product_id,
            'name' => $product->name,
            'image' => $product->images->first()?->image_url ?? '',
            'quantity' => $request->quantity ?? 1,
            'variation_type' => $request->variation_type,
            'variation_value' => $request->variation_value,
            'price' => $request->price,
        ];
        
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $cartItem['quantity'];
            // Update the cartItem to reflect new quantity for response
            $cartItem['quantity'] = $cart[$key]['quantity'];
        } else {
            $cart[$key] = $cartItem;
        }

        session()->put('cart', $cart);

        $totalQuantity = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'status' => 'success',
            'message' => 'تمت إضافة المنتج إلى السلة',
            'cartCount' => count($cart),
            'totalQuantity' => $totalQuantity,
            'item' => $cartItem // Return the actual item stored/updated
        ]);
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        unset($cart[$request->key]);
        session()->put('cart', $cart);

        return back()->with('success', 'تم إزالة المنتج من السلة');
    }

    public function checkout()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

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
            'user_id' => Auth::id(),
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

        return redirect()->route('dashboard')
            ->with('success', 'تم إنشاء الطلب بنجاح. رقم الطلب: ' . $order->id);
    }
}
