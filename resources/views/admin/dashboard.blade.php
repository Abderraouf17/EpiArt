@extends('admin.layout')

@section('title', 'لوحة التحكم')
@section('header', 'لوحة التحكم')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <h3>إجمالي المستخدمين</h3>
        <div class="value">{{ $totalUsers }}</div>
    </div>
    <div class="stat-card">
        <h3>إجمالي المنتجات</h3>
        <div class="value">{{ $totalProducts }}</div>
    </div>
    <div class="stat-card">
        <h3>إجمالي الطلبات</h3>
        <div class="value">{{ $totalOrders }}</div>
    </div>
    <div class="stat-card">
        <h3>الفئات</h3>
        <div class="value">{{ $totalCategories }}</div>
    </div>
    <div class="stat-card">
        <h3>طلبات قيد الانتظار</h3>
        <div class="value">{{ $pendingOrders }}</div>
    </div>
    <div class="stat-card">
        <h3>إجمالي الإيرادات</h3>
        <div class="value">{{ number_format($totalRevenue, 0) }} DA</div>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 1.5rem;">آخر الطلبات</h3>
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
                        <span class="badge badge-@if($order->status === 'delivered') success @elseif($order->status === 'pending') warning @else danger @endif">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="/admin/orders/{{ $order->id }}" class="btn btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">عرض</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
