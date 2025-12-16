@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-orange-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success Header -->
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-green-400 to-green-600 rounded-full mb-6 shadow-lg animate-bounce">
                    <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3">تم إنشاء طلبك بنجاح!</h1>
                <p class="text-xl text-gray-600">شكراً لك على ثقتك بنا. سنتواصل معك قريباً</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6 mb-8">
                <!-- Order Number Card -->
                <div
                    class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-[#8B3A3A] text-center transform hover:scale-105 transition">
                    <div class="text-sm text-gray-500 mb-2">رقم الطلب</div>
                    <div class="text-3xl font-bold text-[#8B3A3A]">#{{ $order->id }}</div>
                </div>

                <!-- Status Card -->
                <div
                    class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-amber-500 text-center transform hover:scale-105 transition">
                    <div class="text-sm text-gray-500 mb-2">حالة الطلب</div>
                    <div class="inline-block px-4 py-2 bg-amber-100 text-amber-800 rounded-full font-semibold">
                        {{ $order->status_label }}
                    </div>
                </div>

                <!-- Total Card -->
                <div
                    class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-green-500 text-center transform hover:scale-105 transition">
                    <div class="text-sm text-gray-500 mb-2">المبلغ الإجمالي</div>
                    <div class="text-3xl font-bold text-green-600">{{ number_format($order->total_price, 0) }} <span
                            class="text-lg">DA</span></div>
                </div>
            </div>

            <!-- Order Details Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6 border border-gray-100">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="w-12 h-12 bg-[#8B3A3A]/10 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#8B3A3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">معلومات العميل</h2>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <div>
                                <div class="text-sm text-gray-500">الاسم</div>
                                <div class="font-semibold text-gray-900">{{ $order->first_name }} {{ $order->last_name }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <div class="text-sm text-gray-500">البريد الإلكتروني</div>
                                <div class="font-semibold text-gray-900">{{ $order->email }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <div>
                                <div class="text-sm text-gray-500">الهاتف</div>
                                <div class="font-semibold text-gray-900">{{ $order->phone }}</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <div class="text-sm text-gray-500">الولاية</div>
                                <div class="font-semibold text-gray-900">{{ $order->wilaya }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($order->address)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <div>
                                <div class="text-sm text-gray-500">العنوان</div>
                                <div class="font-semibold text-gray-900">{{ $order->address }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Order Items Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6 border border-gray-100">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                    <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">المنتجات المطلوبة</h2>
                </div>

                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                            @if($item->product->images->first())
                                <div class="w-20 h-20 rounded-lg overflow-hidden bg-white shadow-sm flex-shrink-0">
                                    <img src="{{ $item->product->images->first()->image_url }}" alt="{{ $item->product->name }}"
                                        class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 text-lg">{{ $item->product->name }}</h3>
                                @if($item->variation_value)
                                    <p class="text-sm text-gray-600 mt-1">
                                        <span class="inline-block px-2 py-1 bg-white rounded text-xs">{{ $item->variation_type }}:
                                            {{ $item->variation_value }}</span>
                                    </p>
                                @endif
                                <p class="text-sm text-gray-600 mt-1">الكمية: <span
                                        class="font-semibold">{{ $item->quantity }}</span></p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-[#8B3A3A]">
                                    {{ number_format($item->price * $item->quantity, 0) }} <span class="text-sm">DA</span></p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-6 border-t-2 border-gray-200 space-y-3">
                    <div class="flex justify-between text-gray-600">
                        <span class="font-medium">المجموع الفرعي</span>
                        <span class="font-semibold">{{ number_format($order->total_price - $order->delivery_fee, 0) }}
                            DA</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span class="font-medium">رسوم التوصيل</span>
                        <span class="font-semibold">{{ number_format($order->delivery_fee, 0) }} DA</span>
                    </div>
                    <div class="flex justify-between text-2xl font-bold text-gray-900 pt-3 border-t border-gray-200">
                        <span>الإجمالي</span>
                        <span class="text-[#8B3A3A]">{{ number_format($order->total_price, 0) }} DA</span>
                    </div>
                </div>
            </div>

            <!-- Email Confirmation Notice -->
            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-blue-500 rounded-xl p-6 mb-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-blue-900 text-lg mb-2">تأكيد عبر البريد الإلكتروني</h3>
                        <p class="text-blue-800">تم إرسال تفاصيل طلبك إلى <strong
                                class="font-bold">{{ $order->email }}</strong></p>
                        <p class="text-sm text-blue-700 mt-1">يرجى التحقق من صندوق الوارد أو مجلد الرسائل غير المرغوب فيها
                        </p>
                    </div>
                </div>
            </div>

            <!-- Create Account CTA (for guests only) -->
            @guest
                <div
                    class="bg-gradient-to-br from-purple-50 via-pink-50 to-orange-50 border border-purple-200 rounded-2xl p-8 mb-6 shadow-lg">
                    <div class="flex items-start gap-6">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">أنشئ حساباً لتتبع طلباتك!</h3>
                            <p class="text-gray-700 mb-4">سجل حساباً الآن واستمتع بمزايا حصرية:</p>
                            <ul class="grid md:grid-cols-2 gap-3 mb-6">
                                <li class="flex items-center gap-2 text-gray-700">
                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>تتبع حالة طلباتك</span>
                                </li>
                                <li class="flex items-center gap-2 text-gray-700">
                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>الوصول إلى سجل الطلبات</span>
                                </li>
                                <li class="flex items-center gap-2 text-gray-700">
                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>حفظ عنوانك للطلبات المستقبلية</span>
                                </li>
                                <li class="flex items-center gap-2 text-gray-700">
                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>إدارة قائمة المفضلة</span>
                                </li>
                            </ul>
                            <a href="{{ route('register') }}"
                                class="inline-block px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-xl hover:from-purple-700 hover:to-pink-700 transition shadow-lg transform hover:scale-105">
                                إنشاء حساب الآن
                            </a>
                        </div>
                    </div>
                </div>
            @endguest

            <!-- Action Buttons -->
            <div class="grid md:grid-cols-2 gap-4">
                <a href="{{ route('shop.index') }}"
                    class="flex items-center justify-center gap-3 px-8 py-4 bg-[#8B3A3A] text-white font-bold rounded-xl hover:bg-[#722F37] transition shadow-lg transform hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>متابعة التسوق</span>
                </a>
                <a href="/"
                    class="flex items-center justify-center gap-3 px-8 py-4 border-2 border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition transform hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>العودة للرئيسية</span>
                </a>
            </div>
        </div>
    </div>

    <style>
        @keyframes bounce {

            0%,
            100% {
                transform: translateY(-5%);
                animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
            }

            50% {
                transform: translateY(0);
                animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
            }
        }

        .animate-bounce {
            animation: bounce 1s infinite;
        }
    </style>
@endsection