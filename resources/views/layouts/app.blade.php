<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|playfair-display:600,700"
        rel="stylesheet" />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased" x-data="shopStore()" @add-to-cart.window="addToCart($event.detail)"
    @show-notification.window="showNotification($event.detail.message, $event.detail.type)"
    @buy-now.window="buyNow($event.detail)" x-init="$store.shopMode = { currentMode: 'spice' }">
    <div class="min-h-screen bg-gray-100 pt-20">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot }}
            @endif
        </main>
    </div>

    <!-- Side Cart -->
    <div x-show="cartOpen" @click.away="cartOpen = false" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed top-0 right-0 h-full w-[85%] sm:w-96 bg-white shadow-2xl z-50 flex flex-col"
        style="display: none;">

        <div class="p-4 md:p-6 border-b flex justify-between items-center"
            style="background: linear-gradient(135deg, #8B3A3A, #722F37);">
            <h2 class="text-xl md:text-2xl font-bold text-white">Your Cart</h2>
            <button @click="cartOpen = false" class="text-white hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 md:p-6">
            <template x-if="cartItems.length === 0">
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <p class="text-gray-500">Your cart is empty</p>
                </div>
            </template>

            <template x-for="item in cartItems" :key="item.id">
                <div
                    class="flex gap-3 md:gap-4 mb-3 md:mb-4 p-3 md:p-4 border rounded-lg hover:shadow-md transition group">
                    <a :href="'/shop/product/' + item.slug" class="flex-shrink-0">
                        <img :src="item.image" :alt="item.name"
                            class="w-16 h-16 md:w-20 md:h-20 object-cover rounded hover:opacity-80 transition">
                    </a>
                    <div class="flex-1 flex flex-col">
                        <a :href="'/shop/product/' + item.slug" class="hover:text-[#8B3A3A] transition">
                            <h3 class="font-semibold text-sm md:text-base text-gray-800" x-text="item.name"></h3>
                        </a>
                        <p class="text-xs md:text-sm text-gray-600 mb-2" x-text="item.price + ' DA'"></p>
                        <div class="flex items-center gap-2 mt-auto">
                            <button @click.stop="updateQuantity(item.id, -1)"
                                class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 transition text-sm">-</button>
                            <span x-text="item.quantity" class="px-2 md:px-3 font-medium text-sm"></span>
                            <button @click.stop="updateQuantity(item.id, 1)"
                                class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 transition text-sm">+</button>
                            <button @click.stop="removeFromCart(item.id)"
                                class="ml-auto text-red-500 hover:text-red-700 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="border-t p-4 md:p-6 bg-gray-50">
            <div class="flex justify-between mb-4">
                <span class="text-base md:text-lg font-semibold text-gray-800">Total:</span>
                <span class="text-base md:text-lg font-bold text-gray-900" x-text="cartTotal() + ' DA'"></span>
            </div>

            <!-- Empty Cart Button -->
            <button @click="clearCart()" x-show="cartItems.length > 0"
                class="w-full mb-3 py-2 md:py-2.5 rounded-lg text-sm md:text-base text-red-600 font-semibold transition-all border-2 border-red-600 hover:bg-red-50">
                Empty Cart
            </button>

            <button @click="goToCheckout()" :disabled="cartItems.length === 0"
                :class="cartItems.length === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-lg transform hover:-translate-y-0.5'"
                class="w-full py-3 rounded-lg text-white text-sm md:text-base font-semibold transition-all"
                style="background: linear-gradient(135deg, #8B3A3A, #722F37);">
                Checkout
            </button>
        </div>
    </div>

    <!-- Search Modal -->
    <div x-show="searchOpen" @click.away="searchOpen = false" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-start justify-center pt-24"
        style="display: none;">
        <div @click.stop
            class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 overflow-hidden transform transition-all"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0">

            <!-- Header with Gradient -->
            <div class="bg-gradient-to-r from-[#8B3A3A] to-[#722F37] p-6">
                <div class="flex items-center gap-4">
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <input type="text" x-model="searchQuery" @input="performSearch"
                            placeholder="Search for spices, beauty products..."
                            class="w-full bg-white/10 backdrop-blur-sm text-white placeholder-white/70 text-lg px-4 py-3 rounded-lg outline-none border-2 border-white/20 focus:border-white/40 transition-all"
                            autofocus>
                    </div>
                    <button @click="searchOpen = false"
                        class="flex-shrink-0 w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-white/30 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Results -->
            <div class="max-h-[500px] overflow-y-auto p-6 bg-gray-50">
                <template x-if="searchQuery.length === 0">
                    <div class="text-center py-16">
                        <div
                            class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-[#8B3A3A]/10 to-[#722F37]/10 rounded-full mb-4">
                            <svg class="w-10 h-10 text-[#8B3A3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 text-lg font-medium">Start typing to search...</p>
                        <p class="text-gray-400 text-sm mt-2">Find your favorite spices and beauty products</p>
                    </div>
                </template>

                <template x-if="searchQuery.length > 0 && searchResults.length === 0">
                    <div class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-200 rounded-full mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-gray-600 text-lg font-medium">No results found</p>
                        <p class="text-gray-400 text-sm mt-2">Try searching with different keywords</p>
                    </div>
                </template>

                <template x-for="result in searchResults" :key="result.id">
                    <a :href="`/shop/product/${result.slug}`"
                        class="flex gap-4 p-4 mb-3 bg-white hover:bg-gradient-to-r hover:from-white hover:to-gray-50 rounded-xl cursor-pointer transition-all duration-300 shadow-sm hover:shadow-md border border-gray-100 group">
                        <div class="flex-shrink-0 w-20 h-20 bg-gray-100 rounded-lg overflow-hidden">
                            <img :src="result.image" :alt="result.name"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 group-hover:text-[#8B3A3A] transition-colors truncate mb-1"
                                x-text="result.name"></h4>
                            <p class="text-[#8B3A3A] font-bold text-lg" x-text="result.price + ' DA'"></p>
                        </div>
                        <div class="flex-shrink-0 flex items-center">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-[#8B3A3A] transition-colors" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                </template>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast-notification" style="
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-left: 4px solid #10b981;
            z-index: 9999;
            transition: transform 0.3s ease;
        ">
        <p id="notification-message" style="margin: 0; color: #1f2937; font-weight: 500;"></p>
    </div>

    <!-- Confirmation Modal -->
    <div x-show="showConfirmModal" x-cloak @click.away="showConfirmModal = false"
        class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-[60] flex items-center justify-center"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
        <div @click.stop class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            <!-- Header -->
            <div class="bg-gradient-to-r from-red-600 to-red-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Confirm Action</h3>
                        <p class="text-white/80 text-sm">This action cannot be undone</p>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6">
                <p class="text-gray-700 text-lg mb-6">Are you sure you want to empty your cart? All items will be
                    removed.</p>

                <!-- Actions -->
                <div class="flex gap-3">
                    <button @click="showConfirmModal = false"
                        class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-all">
                        Cancel
                    </button>
                    <button @click="confirmClearCart()"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg font-semibold hover:shadow-lg transition-all">
                        Empty Cart
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        #toast-notification {
            transform: translateX(400px);
        }

        #toast-notification.show {
            transform: translateX(0);
        }
    </style>

    @include('partials.login-modal')
    <script>
        function shopStore() {
            return {
                open: false,
                cartOpen: false,
                searchOpen: false,
                showConfirmModal: false,
                cartItems: @json(array_values(session('cart', []))),
                wishlistCount: @json(Auth::check() ? Auth::user()->wishlists()->count() : 0),
                searchQuery: '',
                searchResults: [],
                searchTimeout: null,

                cartTotal() {
                    return this.cartItems.reduce((sum, item) => sum + (parseFloat(item.price) * item.quantity), 0);
                },

                updateQuantity(id, delta) {
                    const item = this.cartItems.find(i => i.id == id);
                    if (item) {
                        const newQuantity = parseInt(item.quantity) + delta;
                        if (newQuantity >= 1) {
                            item.quantity = newQuantity;
                            // Ideally sync with server here too
                        }
                    }
                },

                removeFromCart(id) {
                    fetch('/cart/remove', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ key: id })
                    })
                        .then(response => response.json())
                        .then(data => {
                            this.cartItems = this.cartItems.filter(i => i.id != id);
                            this.showNotification('تم إزالة المنتج من السلة', 'success');
                        })
                        .catch(error => {
                            console.error('Error removing item:', error);
                            this.showNotification('فشل في إزالة المنتج', 'error');
                        });
                },


                clearCart() {
                    this.showConfirmModal = true;
                },

                confirmClearCart() {
                    this.showConfirmModal = false;

                    fetch('/cart/clear', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            this.cartItems = [];
                            this.showNotification('تم إفراغ السلة', 'success');
                        })
                        .catch(error => {
                            console.error('Error clearing cart:', error);
                            this.showNotification('فشل في إفراغ السلة', 'error');
                        });
                },

                addToCart(product) {
                    const formData = new FormData();
                    formData.append('product_id', product.id);
                    formData.append('price', product.price);
                    formData.append('quantity', product.quantity || 1);
                    if (product.variation_type) formData.append('variation_type', product.variation_type);
                    if (product.variation_value) formData.append('variation_value', product.variation_value);

                    return fetch('/cart/add', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                        .then(res => {
                            if (res.status === 401) {
                                window.location.href = '/login';
                                throw new Error('Unauthorized');
                            }
                            return res.json();
                        })
                        .then(data => {
                            if (data.status === 'success' && data.item) {
                                const existingIdx = this.cartItems.findIndex(i => i.id == data.item.id);
                                if (existingIdx > -1) {
                                    this.cartItems[existingIdx].quantity = parseInt(data.item.quantity);
                                } else {
                                    this.cartItems.push(data.item);
                                }
                                this.cartOpen = true; // Open cart to show addition
                                this.showNotification('تمت إضافة المنتج إلى السلة', 'success');
                                return data;
                            } else {
                                this.showNotification(data.message || 'Error', 'error');
                                return data;
                            }
                        })
                        .catch(error => {
                            if (error.message !== 'Unauthorized') {
                                console.error('Cart update failed:', error);
                                this.showNotification('فشل تحديث السلة', 'error');
                            }
                            throw error;
                        });
                },

                buyNow(product) {
                    this.addToCart(product).then(data => {
                        if (data && data.status === 'success') {
                            window.location.href = '/checkout';
                        }
                    }).catch(err => console.error(err));
                },

                showNotification(message, type) {
                    const notification = document.getElementById('toast-notification');
                    const notificationMessage = document.getElementById('notification-message');
                    notificationMessage.textContent = message;

                    if (type === 'success') {
                        notification.style.borderLeftColor = '#10b981';
                    } else {
                        notification.style.borderLeftColor = '#ef4444';
                    }

                    notification.style.display = 'block';
                    // Force reflow
                    void notification.offsetWidth;
                    notification.classList.add('show');

                    setTimeout(() => {
                        this.hideNotification();
                    }, 3000);
                },

                hideNotification() {
                    const notification = document.getElementById('toast-notification');
                    if (notification) {
                        notification.classList.remove('show');
                        setTimeout(() => {
                            notification.style.display = 'none';
                        }, 300);
                    }
                },

                performSearch() {
                    // Clear previous timeout
                    if (this.searchTimeout) {
                        clearTimeout(this.searchTimeout);
                    }

                    // Debounce search - wait 300ms after user stops typing
                    this.searchTimeout = setTimeout(() => {
                        if (this.searchQuery.length < 2) {
                            this.searchResults = [];
                            return;
                        }

                        // Call actual API
                        fetch(`/api/search?q=${encodeURIComponent(this.searchQuery)}`)
                            .then(response => response.json())
                            .then(data => {
                                this.searchResults = data;
                            })
                            .catch(error => {
                                console.error('Search error:', error);
                                this.searchResults = [];
                            });
                    }, 300);
                },

                goToCheckout() {
                    if (this.cartItems.length === 0) {
                        this.showNotification('السلة فارغة', 'error');
                        return;
                    }
                    window.location.href = '/checkout';
                }
            }
        }
    </script>
</body>

</html>