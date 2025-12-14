@extends('layouts.app')

@section('content')
@php
    $shippingJson = $shippingRules->toJson();
    $subtotalValue = (float)($subtotal ?? 0);
    // Recalculate subtotal if not passed (fallback)
    if ($subtotalValue == 0 && !empty($cart)) {
        foreach($cart as $item) {
            $subtotalValue += $item['price'] * $item['quantity'];
        }
    }
    
    // Split user name
    $user = auth()->user();
    $nameParts = explode(' ', $user->name, 2);
    $firstName = $nameParts[0];
    $lastName = $nameParts[1] ?? '';
@endphp

<script>
    window.shippingRules = @json($shippingRules);
    window.cartSubtotal = {{ $subtotalValue }};
</script>

<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-serif font-bold text-gray-900 mb-8 text-center">إتمام الشراء</h1>

        @if(empty($cart))
            <div class="max-w-md mx-auto text-center py-16 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="mb-4 text-amber-500">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">السلة فارغة</h2>
                <p class="text-gray-500 mb-6">يرجى إضافة منتجات قبل الشراء.</p>
                <a href="/shop" class="inline-block px-8 py-3 bg-[#8B3A3A] text-white rounded-lg hover:bg-[#722F37] transition font-semibold">تصفح المتجر</a>
            </div>
        @else
            <form method="POST" action="/checkout/process" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf

                <!-- Left Column: Shipping Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Shipping Info Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">معلومات التوصيل</h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الأول</label>
                                <input type="text" name="first_name" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#8B3A3A] focus:border-[#8B3A3A] outline-none transition bg-gray-50 focus:bg-white" value="{{ old('first_name', $firstName) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الأخير</label>
                                <input type="text" name="last_name" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#8B3A3A] focus:border-[#8B3A3A] outline-none transition bg-gray-50 focus:bg-white" value="{{ old('last_name', $lastName) }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#8B3A3A] focus:border-[#8B3A3A] outline-none transition bg-gray-50 focus:bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                                <input type="tel" name="phone" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#8B3A3A] focus:border-[#8B3A3A] outline-none transition bg-gray-50 focus:bg-white" placeholder="05XXXXXXXX" value="{{ old('phone', $user->phone ?? '') }}">
                            </div>
                        </div>

                        <div class="mb-6" x-data="{ 
                            open: false, 
                            search: '', 
                            selected: '', 
                            options: {{ $shippingRules->map(fn($r) => ['value' => $r->wilaya, 'label' => $r->wilaya_code . ' - ' . $r->wilaya])->toJson() }},
                            get filteredOptions() {
                                return this.options.filter(opt => opt.label.toLowerCase().includes(this.search.toLowerCase()));
                            },
                            select(value) {
                                this.selected = value;
                                this.open = false;
                                this.$nextTick(() => {
                                    const input = document.getElementById('wilayaInput');
                                    input.value = value;
                                    input.dispatchEvent(new Event('change', { bubbles: true }));
                                });
                            }
                        }">
                            <label class="block text-sm font-medium text-gray-700 mb-2">الولاية</label>
                            <div class="relative">
                                <input type="hidden" name="wilaya" id="wilayaInput" required>
                                
                                <button @click="open = !open" type="button" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 flex justify-between items-center text-right focus:ring-2 focus:ring-[#8B3A3A] transition">
                                    <span x-text="selected ? options.find(o => o.value == selected).label : 'اختر الولاية'" :class="selected ? 'text-gray-900' : 'text-gray-500'"></span>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="open" @click.away="open = false" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-hidden flex flex-col" style="display: none;">
                                    <div class="p-2 border-b border-gray-100">
                                        <input type="text" x-model="search" placeholder="بحث..." class="w-full px-3 py-2 bg-gray-50 rounded-md border-none focus:ring-0 text-sm">
                                    </div>
                                    <div class="overflow-y-auto flex-1">
                                        <template x-for="option in filteredOptions" :key="option.value">
                                            <div @click="select(option.value)" class="px-4 py-2 hover:bg-gray-50 cursor-pointer text-sm" x-text="option.label"></div>
                                        </template>
                                        <div x-show="filteredOptions.length === 0" class="px-4 py-3 text-gray-500 text-sm text-center">لا توجد نتائج</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">نوع التوصيل</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="delivery_type" value="home" class="peer sr-only" required checked>
                                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-[#8B3A3A] peer-checked:bg-[#8B3A3A]/5 transition flex flex-col items-center gap-3 text-center hover:border-gray-300 h-full">
                                        <div class="w-10 h-10 rounded-full bg-gray-100 peer-checked:bg-[#8B3A3A] flex items-center justify-center transition-colors">
                                            <svg class="w-6 h-6 text-gray-500 peer-checked:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        </div>
                                        <span class="text-sm font-bold text-gray-700 peer-checked:text-[#8B3A3A]">توصيل للمنزل</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="delivery_type" value="desk" class="peer sr-only" required>
                                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-[#8B3A3A] peer-checked:bg-[#8B3A3A]/5 transition flex flex-col items-center gap-3 text-center hover:border-gray-300 h-full">
                                        <div class="w-10 h-10 rounded-full bg-gray-100 peer-checked:bg-[#8B3A3A] flex items-center justify-center transition-colors">
                                            <svg class="w-6 h-6 text-gray-500 peer-checked:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </div>
                                        <span class="text-sm font-bold text-gray-700 peer-checked:text-[#8B3A3A]">توصيل للمكتب</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">العنوان التفصيلي</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#8B3A3A] focus:border-[#8B3A3A] outline-none transition bg-gray-50 focus:bg-white" placeholder="الحي، الشارع، رقم المنزل...">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 sm:p-8 sticky top-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 pb-4 border-b border-gray-100">ملخص الطلب</h2>
                        
                        <div class="space-y-4 max-h-96 overflow-y-auto pr-2 mb-6 custom-scrollbar">
                            @foreach($cart as $item)
                                @php
                                    // Handle missing product if deleted
                                    $product = \App\Models\Product::with('images')->find($item['product_id']);
                                    if(!$product) continue;
                                    
                                    $itemTotal = $item['price'] * $item['quantity'];
                                @endphp
                                <div class="flex gap-4">
                                    <div class="w-16 h-16 rounded-md overflow-hidden bg-gray-100 flex-shrink-0 border border-gray-200">
                                        @if($product->images->first())
                                            <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-bold text-gray-800 line-clamp-1">{{ $product->name }}</h4>
                                        <div class="flex justify-between items-center mt-1">
                                            <p class="text-xs text-gray-500">
                                                @if($item['variation_value'])
                                                    {{ $item['variation_value'] }} × 
                                                @endif
                                                {{ $item['quantity'] }}
                                            </p>
                                            <p class="text-sm font-semibold text-[#8B3A3A]">{{ number_format($itemTotal, 2) }} DA</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="space-y-3 pt-6 border-t border-gray-100">
                            <div class="flex justify-between text-gray-600">
                                <span>المجموع الفرعي</span>
                                <span>{{ number_format($subtotalValue, 2) }} DA</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>الشحن</span>
                                <span id="delivery-fee" class="text-amber-600 font-medium">--</span>
                            </div>
                            <div class="flex justify-between text-xl font-bold text-gray-900 pt-4 border-t border-gray-100">
                                <span>الإجمالي</span>
                                <span id="total" class="text-[#8B3A3A]">{{ number_format($subtotalValue, 2) }} DA</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-8 bg-[#8B3A3A] text-white py-4 rounded-xl font-bold text-lg hover:bg-[#722F37] shadow-lg shadow-[#8B3A3A]/20 transition transform hover:-translate-y-0.5 active:scale-[0.98]">
                            تأكيد الطلب
                        </button>
                        
                        <div class="mt-6 flex items-center justify-center gap-2 text-gray-400 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>دفع آمن 100%</span>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
</style>

<script type="text/javascript">
    'use strict';
    const shippingRules = window.shippingRules || [];
    const subtotal = parseFloat(window.cartSubtotal || 0);

    const wilayaSelect = document.getElementById('wilayaInput');
    const deliveryRadios = document.querySelectorAll('input[name="delivery_type"]');

    if (wilayaSelect) {
        wilayaSelect.addEventListener('change', updateDeliveryFee);
    }
    
    if (deliveryRadios.length > 0) {
        deliveryRadios.forEach(radio => {
            radio.addEventListener('change', updateDeliveryFee);
        });
    }

    function updateDeliveryFee() {
        const wilaya = wilayaSelect.value;
        const deliveryType = document.querySelector('input[name="delivery_type"]:checked')?.value;

        if (!wilaya || !deliveryType) {
            document.getElementById('delivery-fee').textContent = '--';
            document.getElementById('total').textContent = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(subtotal) + ' DA';
            return;
        }

        const rule = shippingRules.find(r => r.wilaya === wilaya);
        if (rule) {
            const fee = deliveryType === 'home' ? parseFloat(rule.home_delivery_fee) : parseFloat(rule.desk_delivery_fee);
            const total = subtotal + fee;
            
            document.getElementById('delivery-fee').textContent = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(fee) + ' DA';
            document.getElementById('total').textContent = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(total) + ' DA';
        }
    }
</script>
@endsection
```