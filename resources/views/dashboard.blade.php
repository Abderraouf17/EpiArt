<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Active Orders -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">الطلبات النشطة</h3>
                    @if($activeOrders->count() > 0)
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-right pb-2">رقم الطلب</th>
                                    <th class="text-right pb-2">المجموع</th>
                                    <th class="text-right pb-2">الحالة</th>
                                    <th class="text-right pb-2">التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeOrders as $order)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3">#{{ $order->id }}</td>
                                        <td>{{ number_format($order->total_price, 2) }} DA</td>
                                        <td><span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">{{ $order->status_label }}</span></td>
                                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500">لا توجد طلبات نشطة</p>
                    @endif
                </div>
            </div>

            <!-- Order History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">سجل الطلبات</h3>
                    @if($orders->count() > 0)
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-right pb-2">رقم الطلب</th>
                                    <th class="text-right pb-2">المجموع</th>
                                    <th class="text-right pb-2">الحالة</th>
                                    <th class="text-right pb-2">التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3">#{{ $order->id }}</td>
                                        <td>{{ number_format($order->total_price, 2) }} DA</td>
                                        <td>
                                            @if($order->status === 'delivered')
                                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">{{ $order->status_label }}</span>
                                            @elseif($order->status === 'cancelled')
                                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">{{ $order->status_label }}</span>
                                            @else
                                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">{{ $order->status_label }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500">لم تقم بأي طلبات بعد</p>
                    @endif
                </div>
            </div>

            <!-- Wishlist -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">المنتجات المفضلة</h3>
                    @if($wishlistProducts->count() > 0)
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
                            @foreach($wishlistProducts as $product)
                                <div style="background: white; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb;">
                                    @if($product->images->first())
                                        <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 200px; object-fit: cover;">
                                    @endif
                                    <div style="padding: 1rem;">
                                        <h4 style="font-weight: 600; margin-bottom: 0.5rem;">{{ $product->name }}</h4>
                                        <p style="color: #8B3A3A; font-weight: bold; margin-bottom: 1rem;">{{ number_format($product->price, 0) }} DA</p>
                                        <a href="/shop/product/{{ $product->slug }}" style="display: block; text-align: center; padding: 0.5rem; background: #8B3A3A; color: white; border-radius: 4px; text-decoration: none; font-weight: 600;">عرض المنتج</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">لا توجد منتجات مفضلة</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

