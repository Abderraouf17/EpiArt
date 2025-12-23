@extends('admin.layout')

@section('title', 'لوحة التحكم')
@section('header', 'لوحة التحكم')

@section('content')
    <!-- Stats Grid - Responsive -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
        <div class="stat-card">
            <h3 class="text-sm md:text-base">إجمالي المستخدمين</h3>
            <div class="value text-2xl md:text-3xl">{{ $totalUsers }}</div>
        </div>
        <div class="stat-card">
            <h3 class="text-sm md:text-base">إجمالي المنتجات</h3>
            <div class="value text-2xl md:text-3xl">{{ $totalProducts }}</div>
        </div>
        <div class="stat-card">
            <h3 class="text-sm md:text-base">إجمالي الطلبات</h3>
            <div class="value text-2xl md:text-3xl">{{ $totalOrders }}</div>
        </div>
        <div class="stat-card">
            <h3 class="text-sm md:text-base">الفئات</h3>
            <div class="value text-2xl md:text-3xl">{{ $totalCategories }}</div>
        </div>
        <div class="stat-card">
            <h3 class="text-sm md:text-base">طلبات قيد الانتظار</h3>
            <div class="value text-2xl md:text-3xl">{{ $pendingOrders }}</div>
        </div>
        <div class="stat-card">
            <h3 class="text-sm md:text-base">إجمالي الإيرادات</h3>
            <div class="value text-2xl md:text-3xl">{{ number_format($totalRevenue, 0) }} DA</div>
        </div>
    </div>

    <div class="card">
        <h3 class="text-lg md:text-xl font-bold mb-4 md:mb-6">آخر الطلبات</h3>

        <!-- Desktop: Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>العميل</th>
                        <th>الإجمالي</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                            <td>{{ number_format($order->total_price, 2) }} DA</td>
                            <td>
                                <span
                                    class="badge badge-@if($order->status === 'delivered') success @elseif($order->status === 'pending') warning @else danger @endif">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="/admin/orders/{{ $order->id }}" class="btn btn-secondary"
                                    style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">عرض</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile: Card View -->
        <div class="md:hidden space-y-4">
            @foreach($recentOrders as $order)
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="text-sm text-gray-500">رقم الطلب</p>
                            <p class="font-bold text-lg">#{{ $order->id }}</p>
                        </div>
                        <span
                            class="badge badge-@if($order->status === 'delivered') success @elseif($order->status === 'pending') warning @else danger @endif">
                            {{ $order->status_label }}
                        </span>
                    </div>

                    <div class="space-y-2 mb-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">العميل:</span>
                            <span class="text-sm font-medium">{{ $order->first_name }} {{ $order->last_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">الإجمالي:</span>
                            <span class="text-sm font-bold text-green-600">{{ number_format($order->total_price, 2) }} DA</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">التاريخ:</span>
                            <span class="text-sm">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>

                    <a href="/admin/orders/{{ $order->id }}" class="btn btn-secondary w-full text-center py-2">عرض التفاصيل</a>
                </div>
            @endforeach
        </div>
    </div>
@endsection