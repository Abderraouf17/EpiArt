<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('is_admin', false)->count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCategories = Category::count();
        
        $recentOrders = Order::latest()->take(10)->get();
        $pendingOrders = Order::where('status', 'pending')->count();
        
        $totalRevenue = Order::where('status', '!=', 'cancelled')
            ->sum('total_price');

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'totalCategories' => $totalCategories,
            'recentOrders' => $recentOrders,
            'pendingOrders' => $pendingOrders,
            'totalRevenue' => $totalRevenue,
        ]);
    }
}
