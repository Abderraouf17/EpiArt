<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaimOrdersController extends Controller
{
    /**
     * Check for claimable orders by email
     */
    public function check()
    {
        $user = Auth::user();

        // Find orders with matching email and no user_id
        $claimableOrders = Order::where('email', $user->email)
            ->whereNull('user_id')
            ->get();

        return response()->json([
            'count' => $claimableOrders->count(),
            'orders' => $claimableOrders
        ]);
    }

    /**
     * Claim orders and link them to user account
     */
    public function claim(Request $request)
    {
        $user = Auth::user();

        // Update orders with user's ID
        $updated = Order::where('email', $user->email)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);

        return response()->json([
            'success' => true,
            'claimed' => $updated,
            'message' => "تم ربط {$updated} طلب بحسابك بنجاح"
        ]);
    }
}
