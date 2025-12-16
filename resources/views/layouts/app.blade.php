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
    @buy-now.window="buyNow($event.detail)">
    <div class="min-h-screen bg-gray-100">
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
        class="fixed top-0 right-0 h-full w-96 bg-white shadow-2xl z-50 flex flex-col" style="display: none;">

        <div class="p-6 border-b flex justify-between items-center"
            style="background: linear-gradient(135deg, #8B3A3A, #722F37);">
            <h2 class="text-2xl font-bold text-white">Your Cart</h2>
            <button @click="cartOpen = false" class="text-white hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6">
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
                <div class="flex gap-4 mb-4 p-4 border rounded-lg hover:shadow-md transition relative group">
                    <a :href="'/shop/product/' + item.slug" class="absolute inset-0 z-0"></a>
                    <img :src="item.image" :alt="item.name" class="w-20 h-20 object-cover rounded relative z-0">
                    <div class="flex-1 relative z-0">
                        <h3 class="font-semibold text-gray-800" x-text="item.name"></h3>
                        <p class="text-sm text-gray-600" x-text="item.price + ' DA'"></p>
                        <div class="flex items-center gap-2 mt-2 relative z-20">
                            <button @click="updateQuantity(item.id, -1)"
                                class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300">-</button>
                            <span x-text="item.quantity" class="px-3"></span>
                            <button @click="updateQuantity(item.id, 1)"
                                class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300">+</button>
                            <button @click="removeFromCart(item.id)" class="ml-auto text-red-500 hover:text-red-700">
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

        <div class="border-t p-6 bg-gray-50">
            <div class="flex justify-between mb-4">
                <span class="text-lg font-semibold text-gray-800">Total:</span>
                <span class="text-lg font-bold text-gray-900" x-text="cartTotal() + ' DA'"></span>
            </div>
            <button @click="goToCheckout()" :disabled="cartItems.length === 0"
                :class="cartItems.length === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-lg transform hover:-translate-y-0.5'"
                class="w-full py-3 rounded-lg text-white font-semibold transition-all"
                style="background: linear-gradient(135deg, #8B3A3A, #722F37);">
                Checkout
            </button>
        </div>
    </div>

    <!-- Search Modal -->
    <div x-show="searchOpen" 
         @click.away="searchOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center pt-20"
         style="display: none;">
        <div @click.stop class="bg-white rounded-lg shadow-2xl w-full max-w-2xl mx-4">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="searchQuery" @input="performSearch"
                           placeholder="Search for products..."
                           class="flex-1 text-lg outline-none" autofocus>
                    <button @click="searchOpen = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="border-t pt-4 max-h-96 overflow-y-auto">
                    <template x-if="searchQuery.length === 0">
                        <p class="text-gray-400 text-center py-8">Start typing to search...</p>
                    </template>
                    <template x-if="searchQuery.length > 0 && searchResults.length === 0">
                        <p class="text-gray-400 text-center py-8">No results found</p>
                    </template>
                    <template x-for="result in searchResults" :key="result.id">
                        <a :href="`/shop/product/${result.slug}`" class="flex gap-4 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                            <img :src="result.image" :alt="result.name" class="w-16 h-16 object-cover rounded">
                            <div>
                                <h4 class="font-semibold text-gray-800" x-text="result.name"></h4>
                                <p class="text-sm text-gray-600" x-text="result.price + ' DA'"></p>
                            </div>
                        </a>
                    </template>
                </div>
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
                    this.cartItems = this.cartItems.filter(i => i.id != id);
                    fetch('/cart/remove', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ key: id })
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