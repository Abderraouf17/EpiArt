@extends('admin.layout')

@section('title', 'الطلبات')
@section('header', 'إدارة الطلبات')

@section('content')
<div class="card">
    <h3 style="margin-bottom: 1.5rem;">قائمة الطلبات</h3>

    <table class="table">
        <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>العميل</th>
                <th>البريد الإلكتروني</th>
                <th>الإجمالي</th>
                <th>الحالة</th>
                <th>التاريخ</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                    <td>{{ $order->email }}</td>
                    <td>{{ number_format($order->total_price, 2) }} DA</td>
                    <td>
                        <x-order-status :order="$order" />
                    </td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="/admin/orders/{{ $order->id }}" class="btn btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">عرض</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 1.5rem;">
        {{ $orders->links() }}
    </div>
</div>
@endsection
