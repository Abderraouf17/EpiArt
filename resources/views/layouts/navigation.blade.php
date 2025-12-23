<nav class="bg-white/95 backdrop-blur-sm shadow-sm fixed w-full top-0 z-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center gap-2">
                <a href="/">
                    <img src="/logo/logo-main.png" alt="EpiArt" class="h-12 w-auto">
                </a>
            </div>

            <!-- Center: Home Button (Dashboard only) -->
            <div class="hidden md:flex items-center gap-3">
                @if(request()->is('dashboard') || request()->is('dashboard/*'))
                    <!-- Home Button - Shows when on dashboard -->
                    <a href="/"
                        class="px-6 py-2 bg-gray-800 text-white rounded-full text-sm font-semibold hover:bg-gray-700 transition-all duration-300 shadow-md hover:shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Home
                    </a>
                @endif
            </div>

            <!-- Right Actions -->
            <div class="flex items-center space-x-4">
                <!-- Search Button -->
                <button @click="searchOpen = true"
                    class="p-2 text-gray-600 hover:text-gray-900 transition-colors relative group">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- Wishlist -->
                <a href="/wishlist" class="p-2 text-gray-600 hover:text-gray-900 transition-colors relative group">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </a>

                <!-- Cart -->
                <button @click="cartOpen = true"
                    class="p-2 text-gray-600 hover:text-gray-900 transition-colors relative group">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span x-show="cartItems.length > 0"
                        class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold"
                        x-text="cartItems.length"></span>
                </button>

                <!-- Auth Buttons -->
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-full text-white text-sm font-bold transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 hover:scale-105 ring-1 ring-black/5"
                            style="background: linear-gradient(135deg,#8B3A3A,#722F37); box-shadow: 0 6px 18px rgba(139,58,58,0.2)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-full text-white text-sm font-bold transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 hover:scale-105 ring-1 ring-black/5"
                            style="background: linear-gradient(135deg,#8B3A3A,#722F37); box-shadow: 0 6px 18px rgba(139,58,58,0.2)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profile
                        </a>
                    @endif

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-full text-white text-sm font-bold transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 hover:scale-105 ring-1 ring-black/5"
                            style="background: linear-gradient(135deg,#dc2626,#b91c1c); box-shadow: 0 6px 18px rgba(220,38,38,0.2)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-full text-white text-sm font-bold transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 hover:scale-105 ring-1 ring-black/5"
                        style="background: linear-gradient(135deg,#8B3A3A,#722F37); box-shadow: 0 6px 18px rgba(139,58,58,0.2)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Log in
                    </a>
                    <a href="{{ route('register') }}"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-full text-white text-sm font-bold transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 hover:scale-105 ring-1 ring-black/5"
                        style="background: linear-gradient(135deg,#7f1d1d,#8B3A3A); box-shadow: 0 8px 24px rgba(127,29,29,0.22)">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>