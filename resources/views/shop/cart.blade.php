@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-12">
        <h1 class="text-2xl md:text-3xl font-bold mb-6 md:mb-8">السلة</h1>

        @if(empty($cart))
            <div class="text-center py-12 md:py-16">
                <p class="text-gray-600 mb-6 text-base md:text-lg">السلة فارغة</p>
                <a href="/shop"
                    class="inline-block px-6 py-3 bg-[#8B3A3A] text-white rounded-lg font-semibold hover:bg-[#722F37] transition">تسوق
                    الآن</a>
            </div>
        @else
            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 mb-8 lg:mb-0">
                    <!-- Desktop Table View -->
                    <div class="hidden md:block">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="py-4 px-4 text-right font-semibold text-gray-700">المنتج</th>
                                    <th class="py-4 px-4 text-right font-semibold text-gray-700">الكمية</th>
                                    <th class="py-4 px-4 text-right font-semibold text-gray-700">السعر</th>
                                    <th class="py-4 px-4 font-semibold text-gray-700">الإجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($cart as $key => $item)
                                    @php
                                        $subtotal = $item['price'] * $item['quantity'];
                                        $total += $subtotal;
                                    @endphp
                                    <tr class="border-b border-gray-100">
                                        <td class="py-4 px-4">
                                            <div>
                                                <p class="font-semibold text-gray-900">
                                                    {{ \App\Models\Product::find($item['product_id'])->name }}</p>
                                                @if($item['variation_value'])
                                                    <small class="text-gray-500">{{ $item['variation_type'] }}:
                                                        {{ $item['variation_value'] }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-gray-900">{{ $item['quantity'] }}</td>
                                        <td class="py-4 px-4 font-semibold text-gray-900">{{ number_format($subtotal, 2) }} DA</td>
                                        <td class="py-4 px-4">
                                            <form method="POST" action="/cart/remove" class="inline">
                                                @csrf
                                                <input type="hidden" name="key" value="{{ $key }}">
                                                <button type="submit"
                                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm font-medium">حذف</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="md:hidden space-y-4">
                        @php $total = 0; @endphp
                        @foreach($cart as $key => $item)
                            @php
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                            @endphp
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 text-base">
                                            {{ \App\Models\Product::find($item['product_id'])->name }}</h3>
                                        @if($item['variation_value'])
                                            <p class="text-sm text-gray-500 mt-1">{{ $item['variation_type'] }}:
                                                {{ $item['variation_value'] }}</p>
                                        @endif
                                    </div>
                                    <form method="POST" action="/cart/remove" class="ml-2">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $key }}">
                                        <button type="submit" class="text-red-600 hover:text-red-800 p-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                    <span class="text-sm text-gray-600">الكمية: <span
                                            class="font-semibold text-gray-900">{{ $item['quantity'] }}</span></span>
                                    <span class="text-lg font-bold text-[#8B3A3A]">{{ number_format($subtotal, 2) }} DA</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 sticky top-24">
                        <h3 class="text-lg font-semibold mb-4">ملخص الطلب</h3>
                        <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-200">
                            <span class="text-gray-700">المجموع:</span>
                            <span class="text-xl font-bold text-gray-900">{{ number_format($total, 2) }} DA</span>
                        </div>
                        <a href="/checkout"
                            class="block text-center py-3 bg-[#8B3A3A] text-white rounded-lg font-semibold hover:bg-[#722F37] transition mb-3">متابعة
                            الشراء</a>
                        <a href="/shop"
                            class="block text-center py-3 bg-white text-[#8B3A3A] border-2 border-[#8B3A3A] rounded-lg font-semibold hover:bg-gray-50 transition">مواصلة
                            التسوق</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection