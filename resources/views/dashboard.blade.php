<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Order Claiming Modal -->
    @if(session('claimable_orders'))
        <div x-data="{ showClaimModal: true }" x-show="showClaimModal"
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" style="display: none;">
            <div class="bg-white rounded-lg p-8 max-w-md mx-4 shadow-2xl">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">طلبات سابقة!</h3>
                </div>
                <p class="text-gray-700 mb-6 text-center">
                    وجدنا <strong class="text-green-600">{{ session('claimable_orders') }}</strong> طلب/طلبات مرتبطة بعنوان
                    بريدك الإلكتروني.
                    هل تريد إضافتها إلى حسابك لتتمكن من تتبعها؟
                </p>
                <div class="flex gap-4">
                    <button @click="claimOrders()"
                        class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                        نعم، أضف الطلبات
                    </button>
                    <button @click="showClaimModal = false"
                        class="flex-1 bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-400 transition">
                        لا، شكراً
                    </button>
                </div>
            </div>
        </div>

        <script>
            function claimOrders() {
                fetch('/claim-orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error claiming orders:', error);
                        alert('حدث خطأ أثناء ربط الطلبات');
                    });
            }
        </script>
    @endif

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
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold {{ $order->status_color }}">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                        <td class="text-gray-500 text-sm">{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <button onclick="openOrderDetails({{ $order->id }})"
                                                class="text-[#8B3A3A] hover:text-[#722F37] font-semibold text-sm">
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
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold {{ $order->status_color }}">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                        <td class="text-gray-500 text-sm">{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <button onclick="openOrderDetails({{ $order->id }})"
                                                class="text-[#8B3A3A] hover:text-[#722F37] font-semibold text-sm">
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
                        <a href="{{ route('wishlist.index') }}" class="text-sm text-[#8B3A3A] hover:underline">عرض
                            الكل</a>
                    </div>
                    @if($wishlistProducts->count() > 0)
                        <div
                            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
                            @foreach($wishlistProducts->take(4) as $product)
                                <div
                                    style="background: white; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb; transition: transform 0.3s; hover:shadow-md;">
                                    @if($product->images->first())
                                        <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}"
                                            style="width: 100%; height: 150px; object-fit: cover;">
                                    @endif
                                    <div style="padding: 1rem;">
                                        <h4
                                            style="font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $product->name }}</h4>
                                        <p style="color: #8B3A3A; font-weight: bold; margin-bottom: 1rem;">
                                            {{ number_format($product->price, 0) }} DA</p>
                                        <a href="/shop/product/{{ $product->slug }}"
                                            style="display: block; text-align: center; padding: 0.5rem; background: #8B3A3A; color: white; border-radius: 4px; text-decoration: none; font-size: 0.85rem;">عرض</a>
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