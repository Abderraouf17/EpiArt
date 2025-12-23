@extends('admin.layout')

@section('title', 'الطلبات')
@section('header', 'إدارة الطلبات')

@section('content')
    <div class="card">
        <h3 style="margin-bottom: 1.5rem;">قائمة الطلبات</h3>

        <!-- Mobile View (Cards) -->
        <div class="md:hidden space-y-4">
            @foreach($orders as $order)
                <div class="bg-white border rounded-lg p-4 shadow-sm">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <span class="font-bold text-lg">#{{ $order->id }}</span>
                            <span class="text-sm text-gray-500 block">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <x-order-status :order="$order" />
                    </div>

                    <div class="space-y-2 mb-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">العميل:</span>
                            <span class="font-medium">{{ $order->first_name }} {{ $order->last_name }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">البريد:</span>
                            <span class="font-medium">{{ $order->email }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">الإجمالي:</span>
                            <span class="font-bold text-[#8B3A3A]">{{ number_format($order->total_price, 2) }} DA</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t mt-2">
                        <a href="/admin/orders/{{ $order->id }}"
                            class="btn btn-secondary w-full text-center block text-sm py-2">عرض التفاصيل</a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop View (Table) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="table w-full">
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
                                <a href="/admin/orders/{{ $order->id }}" class="btn btn-secondary"
                                    style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">عرض</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $orders->links() }}
        </div>
    </div>
@endsection