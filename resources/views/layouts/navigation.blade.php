<nav x-data="{ 
    open: false, 
    cartOpen: false, 
    cartItems: [],
    cartTotal() {
        return this.cartItems.reduce((sum, item) => sum + (parseFloat(item.price) * item.quantity), 0);
    },
    updateQuantity(id, delta) {
        const item = this.cartItems.find(i => i.id == id);
        if (item) {
            item.quantity += delta;
            if (item.quantity <= 0) {
                this.removeFromCart(id);
            }
        }
    },
    removeFromCart(id) {
        this.cartItems = this.cartItems.filter(i => i.id != id);
    },
    addToCart(product) {
        const existing = this.cartItems.find(i => i.id == product.id);
        if (existing) {
            existing.quantity++;
        } else {
            this.cartItems.push({...product, quantity: 1});
        }
        showNotification('تمت إضافة المنتج إلى السلة', 'success');
    }
}" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/" class="flex items-center gap-2">
                        <img src="/images/logo.png" alt="EpiArt" class="h-10 w-auto">
                        <span class="text-2xl font-bold text-gray-800">EpiArt</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        @if(!Auth::user()->is_admin)
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                        @endif
                    @endauth
                    <x-nav-link :href="route('shop.index')">
                        Shop
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Side Navigation -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                <!-- Search Button -->
                <button class="flex items-center gap-2 px-3 py-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span class="text-sm font-medium hidden md:inline">Search</span>
                </button>

                <!-- Cart Button -->
                <button @click="cartOpen = true" class="relative flex items-center gap-2 px-3 py-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span class="text-sm font-medium hidden md:inline">Cart</span>
                    <span x-show="cartItems.length > 0" x-text="cartItems.length" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"></span>
                </button>

                @auth
                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth
    </div>

    <!-- Side Cart -->
    <div x-show="cartOpen" @click.away="cartOpen = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed top-0 right-0 h-full w-96 bg-white shadow-2xl z-50 flex flex-col" style="display: none;">
        <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Your Cart</h2>
            <button @click="cartOpen = false" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-6">
            <template x-if="cartItems.length === 0">
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <p>Your cart is empty</p>
                </div>
            </template>
            
            <template x-for="item in cartItems" :key="item.id">
                <div class="flex gap-4 mb-4 pb-4 border-b">
                    <img :src="item.image" :alt="item.name" class="w-20 h-20 object-cover rounded">
                    <div class="flex-1">
                        <h3 class="font-semibold" x-text="item.name"></h3>
                        <p class="text-sm text-gray-500" x-text="item.price + ' DA'"></p>
                        <div class="flex items-center gap-2 mt-2">
                            <button @click="updateQuantity(item.id, -1)" class="px-2 py-1 bg-gray-200 rounded">-</button>
                            <span x-text="item.quantity"></span>
                            <button @click="updateQuantity(item.id, 1)" class="px-2 py-1 bg-gray-200 rounded">+</button>
                        </div>
                    </div>
                    <button @click="removeFromCart(item.id)" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </template>
        </div>
        
        <div class="p-6 border-t">
            <div class="flex justify-between mb-4 text-lg font-bold">
                <span>Total:</span>
                <span x-text="cartTotal() + ' DA'"></span>
            </div>
            <a href="/checkout" class="block w-full bg-burgundy text-white text-center py-3 rounded-lg font-semibold hover:bg-burgundy-dark transition" style="background-color: #8B3A3A;">
                Checkout
            </a>
        </div>
    </div>

    <!-- Notification Toast -->
    <div id="notification" class="fixed top-4 right-4 bg-white border-l-4 shadow-lg rounded-lg p-4 transform translate-x-full transition-transform duration-300 z-50" style="min-width: 300px; display: none;">
        <div class="flex items-center gap-3">
            <div id="notif-icon" class="flex-shrink-0"></div>
            <div class="flex-1">
                <p id="notif-message" class="font-semibold text-gray-800"></p>
            </div>
            <button onclick="hideNotification()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <script>
    function showNotification(message, type = 'success') {
        const notif = document.getElementById('notification');
        const icon = document.getElementById('notif-icon');
        const msg = document.getElementById('notif-message');
        
        msg.textContent = message;
        
        if (type === 'success') {
            notif.style.borderColor = '#10b981';
            icon.innerHTML = '<svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        } else if (type === 'error') {
            notif.style.borderColor = '#ef4444';
            icon.innerHTML = '<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        }
        
        notif.style.display = 'block';
        setTimeout(() => {
            notif.style.transform = 'translateX(0)';
        }, 10);
        
        setTimeout(() => hideNotification(), 3000);
    }
    
    function hideNotification() {
        const notif = document.getElementById('notification');
        notif.style.transform = 'translateX(full)';
        setTimeout(() => {
            notif.style.display = 'none';
        }, 300);
    }
    
    function confirmDelete(message) {
        return new Promise((resolve) => {
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            overlay.innerHTML = `
                <div class="bg-white rounded-lg p-6 max-w-sm mx-4 shadow-xl">
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div>
                            <h3 class="font-bold text-lg text-gray-800">تأكيد الحذف</h3>
                            <p class="text-gray-600 text-sm mt-1">${message}</p>
                        </div>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">إلغاء</button>
                        <button id="confirmBtn" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">حذف</button>
                    </div>
                </div>
            `;
            document.body.appendChild(overlay);
            
            overlay.querySelector('#confirmBtn').onclick = () => {
                overlay.remove();
                resolve(true);
            };
        });
    }
    
    // Listen for cart events from product pages
    window.addEventListener('add-to-cart', (event) => {
        const navElement = document.querySelector('nav[x-data]');
        if (navElement && window.Alpine && typeof Alpine.$data === 'function') {
            const alpineData = Alpine.$data(navElement);
            if (alpineData && alpineData.addToCart) {
                const { id, name, price, image, quantity = 1 } = event.detail;
                for (let i = 0; i < quantity; i++) {
                    alpineData.addToCart({ id, name, price, image });
                }
            }
        }
    });
    
    window.addEventListener('buy-now', (event) => {
        const navElement = document.querySelector('nav[x-data]');
        if (navElement && window.Alpine && typeof Alpine.$data === 'function') {
            const alpineData = Alpine.$data(navElement);
            if (alpineData && alpineData.addToCart) {
                const { id, name, price, image } = event.detail;
                alpineData.addToCart({ id, name, price, image });
                alpineData.cartOpen = true;
            }
        }
    });
    </script>
</nav>
