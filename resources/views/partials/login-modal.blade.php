<!-- Login Required Modal -->
<div x-data="{ loginModalOpen: false }" @open-login-modal.window="loginModalOpen = true">
    <div x-show="loginModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="loginModalOpen = false"></div>

        <!-- Modal -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 transform transition-all"
                @click.stop>
                <!-- Close button -->
                <button @click="loginModalOpen = false"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Icon -->
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">تسجيل الدخول مطلوب</h3>
                    <p class="text-gray-600">لإضافة المنتجات إلى قائمة المفضلة، يجب عليك تسجيل الدخول أولاً</p>
                </div>

                <!-- Buttons -->
                <div class="space-y-3">
                    <a href="/login"
                        class="block w-full bg-[#8B3A3A] text-white text-center py-3 rounded-lg font-semibold hover:bg-[#722F37] transition">
                        تسجيل الدخول
                    </a>
                    <a href="/register"
                        class="block w-full bg-gray-100 text-gray-700 text-center py-3 rounded-lg font-semibold hover:bg-gray-200 transition">
                        إنشاء حساب جديد
                    </a>
                    <button @click="loginModalOpen = false"
                        class="block w-full text-gray-500 text-center py-2 hover:text-gray-700 transition">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>