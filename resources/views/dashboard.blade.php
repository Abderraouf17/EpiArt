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
                                    <th class="text-right pb-2 font-medium text-gray-500">رقم الطلب</th>
                                    <th class="text-right pb-2 font-medium text-gray-500">المجموع</th>
                                    <th class="text-right pb-2 font-medium text-gray-500">الحالة</th>
                                    <th class="text-right pb-2 font-medium text-gray-500">التاريخ</th>
                                    <th class="text-right pb-2 font-medium text-gray-500"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeOrders as $order)
                                    <tr class="border-b hover:bg-gray-50 transition">
                                        <td class="py-4 font-semibold text-gray-700">#{{ $order->id }}</td>
                                        <td class="text-[#8B3A3A] font-bold">{{ number_format($order->total_price, 2) }} DA</td>
                                        <td>
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $order->status_color }}">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                        <td class="text-gray-500 text-sm">{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <button onclick="openOrderDetails({{ $order->id }})" class="text-[#8B3A3A] hover:text-[#722F37] font-semibold text-sm">
                                                التفاصيل
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>لا توجد طلبات نشطة</p>
                        </div>
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
                                    <th class="text-right pb-2 font-medium text-gray-500">رقم الطلب</th>
                                    <th class="text-right pb-2 font-medium text-gray-500">المجموع</th>
                                    <th class="text-right pb-2 font-medium text-gray-500">الحالة</th>
                                    <th class="text-right pb-2 font-medium text-gray-500">التاريخ</th>
                                    <th class="text-right pb-2 font-medium text-gray-500"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr class="border-b hover:bg-gray-50 transition">
                                        <td class="py-4 font-semibold text-gray-700">#{{ $order->id }}</td>
                                        <td class="text-[#8B3A3A] font-bold">{{ number_format($order->total_price, 2) }} DA</td>
                                        <td>
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $order->status_color }}">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                        <td class="text-gray-500 text-sm">{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <button onclick="openOrderDetails({{ $order->id }})" class="text-[#8B3A3A] hover:text-[#722F37] font-semibold text-sm">
                                                التفاصيل
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>لم تقم بأي طلبات بعد</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Wishlist -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">المنتجات المفضلة</h3>
                        <a href="{{ route('wishlist.index') }}" class="text-sm text-[#8B3A3A] hover:underline">عرض الكل</a>
                    </div>
                    @if($wishlistProducts->count() > 0)
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
                            @foreach($wishlistProducts->take(4) as $product)
                                <div style="background: white; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb; transition: transform 0.3s; hover:shadow-md;">
                                    @if($product->images->first())
                                        <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 150px; object-fit: cover;">
                                    @endif
                                    <div style="padding: 1rem;">
                                        <h4 style="font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product->name }}</h4>
                                        <p style="color: #8B3A3A; font-weight: bold; margin-bottom: 1rem;">{{ number_format($product->price, 0) }} DA</p>
                                        <a href="/shop/product/{{ $product->slug }}" style="display: block; text-align: center; padding: 0.5rem; background: #8B3A3A; color: white; border-radius: 4px; text-decoration: none; font-size: 0.85rem;">عرض</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>لا توجد منتجات مفضلة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script>
        window.ordersData = {
            @foreach($orders as $order)
                {{ $order->id }}: @json($order->load('items.product.images')),
            @endforeach
        };
    </script>
    
    @include('partials.order-details-modal')
    @include('partials.order-success-modal')
</x-app-layout>