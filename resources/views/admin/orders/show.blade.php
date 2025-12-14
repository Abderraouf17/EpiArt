@extends('admin.layout')

@section('title', 'تفاصيل الطلب #' . $order->id)
@section('header', 'تفاصيل الطلب')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content: Items and Status -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Status Card -->
        <div class="card flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">الطلب #{{ $order->id }}</h3>
                <p class="text-gray-500 text-sm">{{ $order->created_at->format('Y-m-d H:i') }}</p>
            </div>
            <x-order-status :order="$order" />
        </div>

        <!-- Items Card -->
        <div class="card">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">المنتجات المطلوبة</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead>
                        <tr class="text-gray-500 border-b">
                            <th class="pb-3 font-medium">المنتج</th>
                            <th class="pb-3 font-medium">السعر</th>
                            <th class="pb-3 font-medium">الكمية</th>
                            <th class="pb-3 font-medium">الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        @if($item->product && $item->product->images->first())
                                            <img src="{{ $item->product->images->first()->image_url }}" alt="{{ $item->product->name }}" class="w-12 h-12 rounded object-cover border border-gray-200">
                                        @else
                                            <div class="w-12 h-12 rounded bg-gray-100 flex items-center justify-center text-xs text-gray-400">No Img</div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $item->product ? $item->product->name : 'منتج محذوف' }}</p>
                                            @if($item->variation_value)
                                                <p class="text-xs text-gray-500">{{ $item->variation_type }}: {{ $item->variation_value }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4">{{ number_format($item->price, 2) }}</td>
                                <td class="py-4">{{ $item->quantity }}</td>
                                <td class="py-4 font-bold text-gray-800">{{ number_format($item->subtotal, 2) }} DA</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Totals Mobile (visible on small screens only if needed, but sidebar is better) -->
    </div>

    <!-- Sidebar: Customer & Actions -->
    <div class="space-y-6">
        <!-- Actions Card -->
        <div class="card">
            <h3 class="text-lg font-bold text-gray-800 mb-4">تحديث الحالة</h3>
            <form method="POST" action="/admin/orders/{{ $order->id }}/status">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <select name="status" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8B3A3A] outline-none">
                        <option value="pending" @if($order->status === 'pending') selected @endif>⏳ قيد الانتظار</option>
                        <option value="confirmed" @if($order->status === 'confirmed') selected @endif>✅ مؤكدة</option>
                        <option value="shipped" @if($order->status === 'shipped') selected @endif>🚚 مرسلة</option>
                        <option value="delivered" @if($order->status === 'delivered') selected @endif>📦 مسلمة</option>
                        <option value="cancelled" @if($order->status === 'cancelled') selected @endif>❌ ملغاة</option>
                    </select>
                </div>
                <button type="submit" class="w-full btn btn-primary">تحديث</button>
            </form>
        </div>

        <!-- Customer Info -->
        <div class="card">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">بيانات العميل</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">{{ $order->first_name }} {{ $order->last_name }}</p>
                        <p class="text-gray-500">{{ $order->email }}</p>
                        <p class="text-gray-500">{{ $order->phone }}</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3 pt-3 border-t border-gray-100">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">العنوان ({{ $order->delivery_type === 'home' ? 'منزل' : 'مكتب' }})</p>
                        <p class="text-gray-500">{{ $order->wilaya }}</p>
                        <p class="text-gray-500">{{ $order->address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="card bg-gray-50 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-4">الملخص المالي</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">المجموع الفرعي</span>
                    <span class="font-medium">{{ number_format($order->total_price - $order->delivery_fee, 2) }} DA</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">التوصيل</span>
                    <span class="font-medium">{{ number_format($order->delivery_fee, 2) }} DA</span>
                </div>
                <div class="flex justify-between pt-3 border-t border-gray-200 text-lg font-bold text-[#8B3A3A]">
                    <span>الإجمالي</span>
                    <span>{{ number_format($order->total_price, 2) }} DA</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection