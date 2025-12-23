<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EpiArt - Premium Spices & Beauty Products</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|playfair-display:600,700"
        rel="stylesheet" />

    <!-- Alpine.js for interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Load Tailwind via Vite or CDN -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Instrument Sans', 'sans-serif'],
                            serif: ['Playfair Display', 'serif'],
                        },
                        colors: {
                            spice: {
                                50: '#fdf9f3',
                                500: '#8B3A3A',
                                600: '#722F37',
                                700: '#5a1e25',
                                800: '#991b1b', // Deep Red
                                900: '#7f1d1d',
                            },
                            beauty: {
                                50: '#fdf4ff',
                                100: '#fae8ff',
                                500: '#d946ef', // Pink/Purple
                                600: '#c026d3',
                                800: '#86198f',
                                900: '#701a75',
                            }
                        }
                    }
                }
            }
        </script>
    @endif

    <style>
        [x-cloak] {
            display: none !important;
        }

        .fade-enter-active,
        .fade-leave-active {
            transition: opacity 0.5s ease;
        }

        .fade-enter-from,
        .fade-leave-to {
            opacity: 0;
        }

        /* Hide scrollbar but keep functionality */
        .scrollbar-hide {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        
        .scrollbar-hide::-webkit-scrollbar {
            display: none;  /* Chrome, Safari and Opera */
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-800 bg-white" x-data="shopStore()">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-colors duration-300"
        :class="currentMode === 'spice' ? 'bg-white/90 backdrop-blur-md border-b border-orange-100' : 'bg-white/90 backdrop-blur-md border-b border-pink-100'">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">

                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer" @click="currentMode = 'spice'">
                    <img src="/logo/logo-main.png" alt="EpiArt" class="h-8 md:h-12 w-auto">
                </div>

                <!-- Mode Switcher (Central Toggle) - Tab Buttons - Desktop Only -->
                <div class="hidden md:flex items-center gap-3">
                    <!-- Spices/Beauty Toggle -->
                    <div class="flex bg-gray-100 p-1 rounded-full relative">
                        <div class="w-24 h-8 bg-white rounded-full shadow-sm absolute transition-all duration-300 ease-out top-1"
                            :class="currentMode === 'spice' ? 'left-1' : 'left-[6.25rem]'"></div>

                        <button @click="currentMode = 'spice'; window.scrollTo({ top: 0, behavior: 'smooth' })"
                            class="relative z-10 w-24 h-8 rounded-full text-sm font-semibold transition-colors duration-300 focus:outline-none"
                            :class="currentMode === 'spice' ? 'text-spice-800' : 'text-gray-500 hover:text-gray-700'">
                            Spices
                        </button>
                        <button @click="currentMode = 'beauty'; window.scrollTo({ top: 0, behavior: 'smooth' })"
                            class="relative z-10 w-24 h-8 rounded-full text-sm font-semibold transition-colors duration-300 focus:outline-none"
                            :class="currentMode === 'beauty' ? 'text-beauty-800' : 'text-gray-500 hover:text-gray-700'">
                            Beauty
                        </button>
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center space-x-1 sm:space-x-2 md:space-x-4">
                    <!-- Search Button -->
                    <button @click="searchOpen = true"
                        class="flex items-center gap-1 md:gap-2 p-2 md:px-3 md:py-2 rounded-lg font-medium transition-all hover:shadow-md"
                        :class="currentMode === 'spice' ? 'text-spice-800 hover:bg-spice-50' : 'text-beauty-800 hover:bg-beauty-50'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span class="hidden lg:inline text-sm">Search</span>
                    </button>

                    <!-- Wishlist Button -->
                    <a href="{{ route('wishlist.index') }}"
                        class="flex items-center gap-1 md:gap-2 p-2 md:px-3 md:py-2 rounded-lg font-medium transition-all hover:shadow-md"
                        :class="currentMode === 'spice' ? 'text-spice-800 hover:bg-spice-50' : 'text-beauty-800 hover:bg-beauty-50'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span class="hidden lg:inline text-sm">Wishlist</span>
                    </a>

                    <!-- Cart Button -->
                    <button @click="cartOpen = true"
                        class="relative flex items-center gap-1 md:gap-2 p-2 md:px-3 md:py-2 rounded-lg font-medium transition-all hover:shadow-md"
                        :class="currentMode === 'spice' ? 'text-spice-800 hover:bg-spice-50' : 'text-beauty-800 hover:bg-beauty-50'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="hidden lg:inline text-sm">Cart</span>
                        <span x-show="cartItems.length > 0" x-text="cartItems.length"
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"></span>
                    </button>

                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="hidden sm:flex items-center gap-1 md:gap-2 px-3 md:px-4 py-1.5 md:py-2 rounded-full text-white text-xs md:text-sm font-medium transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                            :class="currentMode === 'spice' ? 'text-white' : 'text-white'"
                            :style="currentMode === 'spice' ? 'background: linear-gradient(135deg,#7f1d1d,#8B3A3A); box-shadow: 0 8px 24px rgba(127,29,29,0.22)' : 'background: linear-gradient(135deg,#701a75,#d946ef); box-shadow: 0 8px 24px rgba(112,26,117,0.12)'">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span class="hidden md:inline">Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="hidden sm:flex items-center gap-1 px-3 md:px-5 py-1.5 md:py-2.5 rounded-full text-white text-xs md:text-sm font-bold transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 hover:scale-105 ring-1 ring-black/5"
                            :class="currentMode === 'spice' ? 'text-white' : 'text-white'"
                            :style="currentMode === 'spice' ? 'background: linear-gradient(135deg,#8B3A3A,#722F37); box-shadow: 0 6px 18px rgba(139,58,58,0.2)' : 'background: linear-gradient(135deg,#d946ef,#86198f); box-shadow: 0 6px 18px rgba(217,70,239,0.12)'">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            <span class="hidden md:inline">Log in</span>
                        </a>
                        <a href="{{ route('register') }}"
                            class="hidden md:flex items-center gap-1 md:gap-2 px-3 md:px-5 py-1.5 md:py-2.5 rounded-full text-white text-xs md:text-sm font-bold transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 hover:scale-105 ring-1 ring-black/5"
                            :class="currentMode === 'spice' ? 'text-white' : 'text-white'"
                            :style="currentMode === 'spice' ? 'background: linear-gradient(135deg,#7f1d1d,#8B3A3A); box-shadow: 0 8px 24px rgba(127,29,29,0.22)' : 'background: linear-gradient(135deg,#701a75,#d946ef); box-shadow: 0 8px 24px rgba(112,26,117,0.12)'">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            <span class="hidden lg:inline">Register</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Toggle (Visible only on small screens) -->
        <div class="md:hidden flex border-t border-gray-100">
            <button @click="currentMode = 'spice'; window.scrollTo({ top: 0, behavior: 'smooth' })" class="flex-1 py-3 text-sm font-medium text-center transition-colors"
                :class="currentMode === 'spice' ? 'bg-spice-50 text-spice-800' : 'bg-white text-gray-500'">
                Spices
            </button>
            <button @click="currentMode = 'beauty'; window.scrollTo({ top: 0, behavior: 'smooth' })" class="flex-1 py-3 text-sm font-medium text-center transition-colors"
                :class="currentMode === 'beauty' ? 'bg-beauty-50 text-beauty-800' : 'bg-white text-gray-500'">
                Beauty
            </button>
        </div>
    </nav>

    <!-- Full Screen Hero Slider -->
    <div class="relative h-[70vh] md:h-screen w-full overflow-hidden top-0 left-0">

        <!-- Spices Hero -->
        <div class="absolute inset-0 transition-opacity duration-700 ease-in-out" x-show="currentMode === 'spice'"
            x-transition.opacity.duration.700ms>

            <img src="/images/mix.jpg" class="absolute inset-0 w-full h-full object-cover" alt="Spices Hero">
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/40"></div>

            <div class="relative z-10 h-full flex flex-col justify-center items-center text-white px-4 sm:px-8 md:px-16 pt-16 md:pt-20">
                <div class="text-center mb-8 md:mb-12">
                    <span class="uppercase tracking-[0.2em] text-xs sm:text-sm mb-3 md:mb-4 font-semibold text-amber-300 block">Authentic
                        Flavors</span>
                    <h1 class="font-serif text-3xl sm:text-4xl md:text-7xl lg:text-8xl mb-4 md:mb-6 font-bold leading-tight">Taste The Tradition</h1>
                    <p class="text-sm sm:text-base md:text-xl text-gray-200 max-w-2xl mx-auto mb-6 md:mb-10 leading-relaxed px-4">
                        Hand-picked spices and rare blends from around the world to elevate your culinary experience.
                    </p>
                    <div class="flex center justify-center flex-col sm:flex-row gap-3 md:gap-4">
                        <button
                            onclick="document.getElementById('all-products').scrollIntoView({behavior: 'smooth'})"
                            class="px-6 sm:px-8 py-3 sm:py-4 bg-amber-600 text-white text-sm sm:text-base font-bold rounded hover:bg-amber-700 transition transform hover:scale-105">
                            EXPLORE SPICES
                        </button>
                        <button
                            onclick="document.getElementById('featured-section').scrollIntoView({behavior: 'smooth'})"
                            class="px-6 sm:px-8 py-3 sm:py-4 bg-white/20 border-2 border-white text-white text-sm sm:text-base font-bold rounded hover:bg-white/30 transition transform hover:scale-105">
                            FEATURED PRODUCTS
                        </button>
                        <button
                            onclick="document.getElementById('new-arrivals-section').scrollIntoView({behavior: 'smooth'})"
                            class="px-6 sm:px-8 py-3 sm:py-4 bg-white/20 border-2 border-white text-white text-sm sm:text-base font-bold rounded hover:bg-white/30 transition transform hover:scale-105">
                            NEW IN PRODUCTS
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Beauty Hero -->
        <div class="absolute inset-0 transition-opacity duration-700 ease-in-out" x-show="currentMode === 'beauty'"
            x-transition.opacity.duration.700ms>

            <img src="/images/EpiArt-story.png" class="absolute inset-0 w-full h-full object-cover" alt="Beauty Hero">
            <div class="absolute inset-0 bg-gradient-to-r from-purple-900/70 to-pink-900/40"></div>

            <div class="relative z-10 h-full flex flex-col justify-center items-center text-white px-4 sm:px-8 md:px-16 pt-16 md:pt-20">
                <div class="text-center mb-8 md:mb-12">
                    <span class="uppercase tracking-[0.2em] text-xs sm:text-sm mb-3 md:mb-4 font-semibold text-pink-300 block">Pure & Organic</span>
                    <h1 class="font-serif text-3xl sm:text-4xl md:text-7xl lg:text-8xl mb-4 md:mb-6 font-bold leading-tight">Radiant Natural Beauty</h1>
                    <p class="text-sm sm:text-base md:text-xl text-gray-200 max-w-2xl mx-auto mb-6 md:mb-10 leading-relaxed px-4">
                        Natural ingredients and traditional beauty products for your wellness journey.
                    </p>
                    <div class="flex center justify-center flex-col sm:flex-row gap-3 md:gap-4">
                        <button
                            onclick="document.getElementById('all-beauty-products').scrollIntoView({behavior: 'smooth'})"
                            class="px-6 sm:px-8 py-3 sm:py-4 bg-pink-600 text-white text-sm sm:text-base font-bold rounded hover:bg-pink-700 transition transform hover:scale-105">
                            DISCOVER BEAUTY
                        </button>
                        <button
                            onclick="document.getElementById('featured-section-beauty').scrollIntoView({behavior: 'smooth'})"
                            class="px-6 sm:px-8 py-3 sm:py-4 bg-white/20 border-2 border-white text-white text-sm sm:text-base font-bold rounded hover:bg-white/30 transition transform hover:scale-105">
                            FEATURED PRODUCTS
                        </button>
                        <button
                            onclick="document.getElementById('new-arrivals-section-beauty').scrollIntoView({behavior: 'smooth'})"
                            class="px-6 sm:px-8 py-3 sm:py-4 bg-white/20 border-2 border-white text-white text-sm sm:text-base font-bold rounded hover:bg-white/30 transition transform hover:scale-105">
                            NEW IN PRODUCTS
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Dynamic Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20 min-h-[500px]">

        <!-- SPICE CONTENT -->
        <div x-show="currentMode === 'spice'" x-transition.opacity.duration.500ms>
            <div class="text-center mb-10 md:mb-16">
                <h2 class="text-2xl md:text-3xl font-serif font-bold text-spice-900 mb-3 md:mb-4">Curated Spice Collections</h2>
                <div class="h-1 w-20 bg-spice-500 mx-auto"></div>
            </div>

            <!-- Categories Grid - Smooth Expanding Animation -->

            <div class="flex justify-center mb-12 md:mb-20">
                <!-- Mobile: Grid Layout -->
                <div class="grid grid-cols-2 gap-3 w-full md:hidden px-4">
                    <!-- 1. Cereals -->
                    <a href="/shop/category/cereals" class="group">
                        <div class="relative w-full h-40 overflow-hidden rounded-xl shadow-md">
                            <img src="/images/cereals.jpg" class="w-full h-full object-cover" alt="Cereals">
                            <div class="absolute inset-0 bg-black/30"></div>
                            <div class="absolute inset-0 flex items-end justify-center pb-4">
                                <h3 class="text-lg font-bold text-white uppercase tracking-wide">Cereals</h3>
                            </div>
                        </div>
                    </a>

                    <!-- 2. Mix -->
                    <a href="/shop/category/mix" class="group">
                        <div class="relative w-full h-40 overflow-hidden rounded-xl shadow-md">
                            <img src="/images/mix.jpg" class="w-full h-full object-cover" alt="Mix">
                            <div class="absolute inset-0 bg-black/30"></div>
                            <div class="absolute inset-0 flex items-end justify-center pb-4">
                                <h3 class="text-lg font-bold text-white uppercase tracking-wide">Mix</h3>
                            </div>
                        </div>
                    </a>

                    <!-- 3. Basics -->
                    <a href="/shop/category/basic-spices" class="group">
                        <div class="relative w-full h-40 overflow-hidden rounded-xl shadow-md">
                            <img src="/images/basic.jpeg" class="w-full h-full object-cover" alt="Basics">
                            <div class="absolute inset-0 bg-black/30"></div>
                            <div class="absolute inset-0 flex items-end justify-center pb-4">
                                <h3 class="text-lg font-bold text-white uppercase tracking-wide">Basics</h3>
                            </div>
                        </div>
                    </a>

                    <!-- 4. Coffee & Tea -->
                    <a href="/shop/category/coffee-tea" class="group">
                        <div class="relative w-full h-40 overflow-hidden rounded-xl shadow-md">
                            <img src="/images/coffeetea.jpg" class="w-full h-full object-cover" alt="Coffee & Tea">
                            <div class="absolute inset-0 bg-black/30"></div>
                            <div class="absolute inset-0 flex items-end justify-center pb-4">
                                <h3 class="text-lg font-bold text-white uppercase tracking-wide">Coffee & Tea</h3>
                            </div>
                        </div>
                    </a>

                    <!-- 5. Oils -->
                    <a href="/shop/category/oils" class="group col-span-2">
                        <div class="relative w-full h-40 overflow-hidden rounded-xl shadow-md">
                            <img src="/images/oils.jpg" class="w-full h-full object-cover" alt="Oils">
                            <div class="absolute inset-0 bg-black/30"></div>
                            <div class="absolute inset-0 flex items-end justify-center pb-4">
                                <h3 class="text-lg font-bold text-white uppercase tracking-wide">Oils</h3>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Desktop: Flex Layout with Hover -->
                <div class="hidden md:flex gap-4 w-full max-w-4xl px-4 h-96">

                    <!-- 1. Cereals -->
                    <a href="/shop/category/cereals"
                        class="group cursor-pointer flex-1 transition-all duration-1000 hover:flex-[2.5]">
                        <div
                            class="relative w-full h-full overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-1000">
                            <img src="/images/cereals.jpg"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                                alt="Cereals">
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-1000">
                            </div>
                            <div
                                class="absolute inset-0 flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                                <h3 class="text-2xl font-bold text-white uppercase tracking-wider">Cereals</h3>
                            </div>
                        </div>
                    </a>

                    <!-- 2. Mix -->
                    <a href="/shop/category/mix"
                        class="group cursor-pointer flex-1 transition-all duration-1000 hover:flex-[2.5]">
                        <div
                            class="relative w-full h-full overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-1000">
                            <img src="/images/mix.jpg"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                                alt="Mix">
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-1000">
                            </div>
                            <div
                                class="absolute inset-0 flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                                <h3 class="text-2xl font-bold text-white uppercase tracking-wider">Mix</h3>
                            </div>
                        </div>
                    </a>

                    <!-- 3. Basics -->
                    <a href="/shop/category/basic-spices"
                        class="group cursor-pointer flex-1 transition-all duration-1000 hover:flex-[2.5]">
                        <div
                            class="relative w-full h-full overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-1000">
                            <img src="/images/basic.jpeg"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                                alt="Basics">
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-1000">
                            </div>
                            <div
                                class="absolute inset-0 flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                                <h3 class="text-2xl font-bold text-white uppercase tracking-wider">Basics</h3>
                            </div>
                        </div>
                    </a>

                    <!-- 4. Coffee & Tea -->
                    <a href="/shop/category/coffee-tea"
                        class="group cursor-pointer flex-1 transition-all duration-1000 hover:flex-[2.5]">
                        <div
                            class="relative w-full h-full overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-1000">
                            <img src="/images/coffeetea.jpg"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                                alt="Coffee & Tea">
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-1000">
                            </div>
                            <div
                                class="absolute inset-0 flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                                <h3 class="text-2xl font-bold text-white uppercase tracking-wider">Coffee & Tea</h3>
                            </div>
                        </div>
                    </a>

                    <!-- 5. Oils -->
                    <a href="/shop/category/oils"
                        class="group cursor-pointer flex-1 transition-all duration-1000 hover:flex-[2.5]">
                        <div
                            class="relative w-full h-full overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-1000">
                            <img src="/images/oils.jpg"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                                alt="Oils">
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-1000">
                            </div>
                            <div
                                class="absolute inset-0 flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                                <h3 class="text-2xl font-bold text-white uppercase tracking-wider">Oils</h3>
                            </div>
                        </div>
                    </a>

                </div>
            </div>

            <!-- Featured Products Section -->
            <div id="featured-section" class="py-12 md:py-20 scroll-mt-24">
                <div class="text-center mb-10 md:mb-16">
                    <h2 class="text-2xl md:text-3xl font-serif font-bold text-spice-900 mb-3 md:mb-4">Featured Products</h2>
                    <div class="h-1 w-20 bg-spice-500 mx-auto"></div>
                </div>

                @if($featuredProducts->count() > 0)
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                        @foreach($featuredProducts->where('type', 'spice')->take(4) as $product)
                            <a href="/shop/product/{{ $product->slug }}" class="group">
                                <div
                                    class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                                        @if($product->images->first())
                                            <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                        @else
                                            <div
                                                class="w-full h-full bg-gradient-to-br from-spice-50 to-spice-100 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-spice-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div
                                            class="absolute top-2 md:top-3 right-2 md:right-3 bg-spice-600 text-white px-2 md:px-3 py-0.5 md:py-1 rounded-full text-xs font-semibold">
                                            Featured</div>
                                    </div>
                                    <div class="p-3 md:p-4">
                                        <h3
                                            class="font-semibold text-sm md:text-base text-gray-900 group-hover:text-spice-600 transition mb-1 md:mb-2 truncate">
                                            {{ $product->name }}
                                        </h3>
                                        <p class="text-gray-600 text-xs md:text-sm mb-2 md:mb-3 line-clamp-2">{{ $product->description }}</p>

                                        <div class="flex justify-between items-center mt-2 md:mt-3">
                                            <p class="text-spice-600 font-bold text-base md:text-lg">{{ number_format($product->price, 0) }}
                                                DA</p>
                                            <div class="flex gap-1.5 md:gap-2 z-20 relative">
                                                <button @click.prevent="addToWishlist({{ $product->id }})"
                                                    class="w-9 h-9 md:w-8 md:h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 transition"
                                                    title="Add to Wishlist">
                                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                    </svg>
                                                </button>
                                                <button
                                                    @click.prevent="addToCart({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }}, image: '{{ $product->images->first()?->image_url ?? '' }}' })"
                                                    class="w-9 h-9 md:w-8 md:h-8 rounded-full bg-red-800 flex items-center justify-center text-white hover:bg-red-500 transition shadow-md"
                                                    title="Add to Cart">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500 text-lg">No featured products yet. Check back soon!</p>
                    </div>
                @endif
            </div>

            <!-- New Arrivals Section -->
            <div id="new-arrivals-section" class="py-12 md:py-20 scroll-mt-24">
                <div class="text-center mb-10 md:mb-16">
                    <h2 class="text-2xl md:text-3xl font-serif font-bold text-spice-900 mb-3 md:mb-4">New in the Kitchen</h2>
                    <div class="h-1 w-20 bg-spice-500 mx-auto"></div>
                </div>

                @if($newProducts->count() > 0)
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                        @foreach($newProducts->where('type', 'spice')->take(4) as $product)
                            <a href="/shop/product/{{ $product->slug }}" class="group">
                                <div
                                    class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                                        @if($product->images->first())
                                            <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                        @else
                                            <div
                                                class="w-full h-full bg-gradient-to-br from-spice-50 to-spice-100 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-spice-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div
                                            class="absolute top-2 md:top-3 right-2 md:right-3 bg-amber-500 text-white px-2 md:px-3 py-0.5 md:py-1 rounded-full text-xs font-semibold">
                                            New</div>
                                    </div>
                                    <div class="p-3 md:p-4">
                                        <h3
                                            class="font-semibold text-sm md:text-base text-gray-900 group-hover:text-spice-600 transition mb-1 md:mb-2 truncate">
                                            {{ $product->name }}
                                        </h3>
                                        <p class="text-gray-600 text-xs md:text-sm mb-2 md:mb-3 line-clamp-2">{{ $product->description }}</p>

                                        <div class="flex justify-between items-center mt-2 md:mt-3">
                                            <p class="text-spice-600 font-bold text-base md:text-lg">{{ number_format($product->price, 0) }}
                                                DA</p>
                                            <div class="flex gap-1.5 md:gap-2 z-20 relative">
                                                <button @click.prevent="addToWishlist({{ $product->id }})"
                                                    class="w-9 h-9 md:w-8 md:h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 transition"
                                                    title="Add to Wishlist">
                                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                    </svg>
                                                </button>
                                                <button
                                                    @click.prevent="addToCart({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }}, image: '{{ $product->images->first()?->image_url ?? '' }}' })"
                                                    class="w-9 h-9 md:w-8 md:h-8 rounded-full bg-red-800 flex items-center justify-center text-white hover:bg-red-500 transition shadow-md"
                                                    title="Add to Cart">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500 text-lg">No new products yet. Check back soon!</p>
                    </div>
                @endif
            </div>

            <!-- ALL PRODUCTS SECTION (SPICE) -->
            <div id="all-products" class="mt-12 md:mt-20 scroll-mt-24" x-data="{ selectedSpiceCategory: 'all' }">
                <div class="text-center mb-6 md:mb-8">
                    <h2 class="text-2xl md:text-4xl font-bold text-spice-900 mb-3 md:mb-4">All Spice Products</h2>
                    <p class="text-sm md:text-base text-gray-600 max-w-2xl mx-auto px-4">Browse our complete collection of premium spices</p>
                </div>

                <!-- Category Filter -->
                <div class="mb-6 md:mb-8 overflow-x-auto scrollbar-hide">
                    <div class="flex flex-nowrap md:flex-wrap justify-start md:justify-center gap-2 md:gap-3 px-4 md:px-0 min-w-max md:min-w-0">
                    <button @click="selectedSpiceCategory = 'all'"
                        :class="selectedSpiceCategory === 'all' ? 'bg-gradient-to-r from-[#8B3A3A] to-[#722F37] text-white shadow-lg' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        class="px-4 md:px-6 py-2 md:py-2.5 rounded-full text-sm md:text-base font-semibold transition-all duration-300 border-2 border-transparent hover:border-[#8B3A3A]">
                        All Products
                    </button>
                    @foreach($spiceCategories as $category)
                        <button @click="selectedSpiceCategory = '{{ $category->slug }}'"
                            :class="selectedSpiceCategory === '{{ $category->slug }}' ? 'bg-gradient-to-r from-[#8B3A3A] to-[#722F37] text-white shadow-lg' : 'bg-white text-gray-700 hover:bg-gray-50'"
                            class="px-4 md:px-6 py-2 md:py-2.5 rounded-full text-sm md:text-base font-semibold transition-all duration-300 border-2 border-transparent hover:border-[#8B3A3A]">
                            {{ $category->name }}
                        </button>
                    @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                    @foreach(App\Models\Product::where('type', 'spice')->with(['images', 'category'])->get() as $product)
                        <a href="/shop/product/{{ $product->slug }}" class="group"
                            x-show="selectedSpiceCategory === 'all' || selectedSpiceCategory === '{{ $product->category?->slug }}'"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95">
                            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                                <div class="relative aspect-square overflow-hidden bg-gray-100">
                                    @if($product->images->first())
                                        <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-spice-50 to-spice-100 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-spice-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 2a8 8 0 100 16 8 8 0 000-16z" />
                                            </svg>
                                        </div>
                                    @endif
                                    @if($product->category)
                                        <div class="absolute top-2 md:top-3 left-2 md:left-3 bg-white/90 backdrop-blur-sm px-2 md:px-3 py-0.5 md:py-1 rounded-full text-xs font-semibold text-spice-800">
                                            {{ $product->category->name }}
                                        </div>
                                    @endif
                                </div>
                                <div class="p-3 md:p-4">
                                    <h3 class="font-semibold text-sm md:text-base text-gray-900 mb-1 md:mb-2 group-hover:text-spice-700 transition-colors">
                                        {{ $product->name }}
                                    </h3>
                                    <p class="text-gray-600 text-xs md:text-sm mb-2 md:mb-3 line-clamp-2">{{ $product->description }}</p>

                                    <div class="flex justify-between items-center mt-2 md:mt-3">
                                        <p class="text-spice-700 font-bold text-base md:text-lg">{{ number_format((float)$product->price, 0) }} DA</p>
                                        <div class="flex gap-1.5 md:gap-2 z-20 relative">
                                            <button @click.prevent="addToWishlist({{ $product->id }})"
                                                class="w-9 h-9 md:w-8 md:h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 transition"
                                                title="Add to Wishlist">
                                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                            </button>
                                            <button
                                                @click.prevent="addToCart({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }}, image: '{{ $product->images->first()?->image_url ?? '' }}' })"
                                                class="w-9 h-9 md:w-8 md:h-8 rounded-full bg-red-800 flex items-center justify-center text-white hover:bg-red-500 transition shadow-md"
                                                title="Add to Cart">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>


        </div>

        <!-- BEAUTY CONTENT -->
        <div x-show="currentMode === 'beauty'" x-transition.opacity.duration.500ms>
            <div class="text-center mb-16">
                <h2 class="text-3xl font-serif font-bold text-purple-900 mb-4">Pure Beauty Collections</h2>
                <div class="h-1 w-20 bg-pink-500 mx-auto"></div>
            </div>

            <!-- Categories Grid - Beauty themed -->
            <div class="flex justify-center mb-20">
                <div class="flex gap-4 w-full max-w-4xl px-4 h-96">
                    
                    <a href="/shop/beauty" class="group cursor-pointer flex-1 transition-all duration-1000 hover:flex-[2.5]">
                        <div class="relative w-full h-full overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-1000">
                            <img src="https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800&h=1200&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="Skincare">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-1000"></div>
                            <div class="absolute inset-0 flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                                <h3 class="text-2xl font-bold text-white uppercase tracking-wider">Skincare</h3>
                            </div>
                        </div>
                    </a>

                    <a href="/shop/beauty" class="group cursor-pointer flex-1 transition-all duration-1000 hover:flex-[2.5]">
                        <div class="relative w-full h-full overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-1000">
                            <img src="https://images.unsplash.com/photo-1522338242992-e1a54906a8da?w=800&h=1200&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="Haircare">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-1000"></div>
                            <div class="absolute inset-0 flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                                <h3 class="text-2xl font-bold text-white uppercase tracking-wider">Haircare</h3>
                            </div>
                        </div>
                    </a>

                    <a href="/shop/beauty" class="group cursor-pointer flex-1 transition-all duration-1000 hover:flex-[2.5]">
                        <div class="relative w-full h-full overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-1000">
                            <img src="https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=800&h=1200&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="Aromatherapy">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-1000"></div>
                            <div class="absolute inset-0 flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                                <h3 class="text-2xl font-bold text-white uppercase tracking-wider">Aromatherapy</h3>
                            </div>
                        </div>
                    </a>

                    <a href="/shop/beauty" class="group cursor-pointer flex-1 transition-all duration-1000 hover:flex-[2.5]">
                        <div class="relative w-full h-full overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-1000">
                            <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=800&h=1200&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="Cosmetics">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-1000"></div>
                            <div class="absolute inset-0 flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                                <h3 class="text-2xl font-bold text-white uppercase tracking-wider">Cosmetics</h3>
                            </div>
                        </div>
                    </a>

                    <a href="/shop/beauty" class="group cursor-pointer flex-1 transition-all duration-1000 hover:flex-[2.5]">
                        <div class="relative w-full h-full overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-1000">
                            <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800&h=1200&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="Wellness">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-1000"></div>
                            <div class="absolute inset-0 flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                                <h3 class="text-2xl font-bold text-white uppercase tracking-wider">Wellness</h3>
                            </div>
                        </div>
                    </a>

                </div>
            </div>

            <!-- Featured Beauty Products -->
            <div class="py-12 md:py-20 scroll-mt-24" id="featured-section-beauty">
                <div class="text-center mb-10 md:mb-16">
                    <h2 class="text-2xl md:text-3xl font-serif font-bold text-purple-900 mb-3 md:mb-4">Featured Beauty Products</h2>
                    <div class="h-1 w-20 bg-pink-500 mx-auto"></div>
                </div>

                @if($featuredProducts->where('type', 'beauty')->count() > 0)
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                        @foreach($featuredProducts->where('type', 'beauty')->take(4) as $product)
                            <a href="/shop/product/{{ $product->slug }}" class="group">
                                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                                        @if($product->images->first())
                                            <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-pink-50 to-purple-100 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-pink-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/></svg>
                                            </div>
                                        @endif
                                        <div class="absolute top-2 md:top-3 right-2 md:right-3 bg-pink-600 text-white px-2 md:px-3 py-0.5 md:py-1 rounded-full text-xs font-semibold">Featured</div>
                                    </div>
                                    <div class="p-3 md:p-4">
                                        <h3 class="font-semibold text-sm md:text-base text-gray-900 group-hover:text-pink-600 transition mb-1 md:mb-2 truncate">{{ $product->name }}</h3>
                                        <p class="text-gray-600 text-xs md:text-sm mb-2 md:mb-3 line-clamp-2">{{ $product->description }}</p>
                                        <div class="flex justify-between items-center mt-2 md:mt-3">
                                            <p class="text-pink-600 font-bold text-base md:text-lg">{{ number_format($product->price, 0) }} DA</p>
                                            <div class="flex gap-1.5 md:gap-2 z-20 relative">
                                                <button @click.prevent="addToWishlist({{ $product->id }})" class="w-9 h-9 md:w-8 md:h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 transition">
                                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                                </button>
                                                <button @click.prevent="addToCart({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }}, image: '{{ $product->images->first()?->image_url ?? '' }}' })" class="w-9 h-9 md:w-8 md:h-8 rounded-full bg-pink-600 flex items-center justify-center text-white hover:bg-pink-700 transition shadow-md">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500 text-lg">No featured beauty products yet. Check back soon!</p>
                    </div>
                @endif
            </div>

            <!-- New Beauty Arrivals -->
            <div class="py-12 md:py-20 scroll-mt-24" id="new-arrivals-section-beauty">
                <div class="text-center mb-10 md:mb-16">
                    <h2 class="text-2xl md:text-3xl font-serif font-bold text-purple-900 mb-3 md:mb-4">New Beauty Essentials</h2>
                    <div class="h-1 w-20 bg-pink-500 mx-auto"></div>
                </div>

                @if($newProducts->where('type', 'beauty')->count() > 0)
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                        @foreach($newProducts->where('type', 'beauty')->take(4) as $product)
                            <a href="/shop/product/{{ $product->slug }}" class="group">
                                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                                        @if($product->images->first())
                                            <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-pink-50 to-purple-100 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-pink-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/></svg>
                                            </div>
                                        @endif
                                        <div class="absolute top-2 md:top-3 right-2 md:right-3 bg-purple-500 text-white px-2 md:px-3 py-0.5 md:py-1 rounded-full text-xs font-semibold">New</div>
                                    </div>
                                    <div class="p-3 md:p-4">
                                        <h3 class="font-semibold text-sm md:text-base text-gray-900 group-hover:text-pink-600 transition mb-1 md:mb-2 truncate">{{ $product->name }}</h3>
                                        <p class="text-gray-600 text-xs md:text-sm mb-2 md:mb-3 line-clamp-2">{{ $product->description }}</p>
                                        <div class="flex justify-between items-center mt-2 md:mt-3">
                                            <p class="text-pink-600 font-bold text-base md:text-lg">{{ number_format($product->price, 0) }} DA</p>
                                            <div class="flex gap-1.5 md:gap-2 z-20 relative">
                                                <button @click.prevent="addToWishlist({{ $product->id }})" class="w-9 h-9 md:w-8 md:h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 transition">
                                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                                </button>
                                                <button @click.prevent="addToCart({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }}, image: '{{ $product->images->first()?->image_url ?? '' }}' })" class="w-9 h-9 md:w-8 md:h-8 rounded-full bg-pink-600 flex items-center justify-center text-white hover:bg-pink-700 transition shadow-md">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500 text-lg">No new beauty products yet. Check back soon!</p>
                    </div>
                @endif
            </div>

            <!-- ALL BEAUTY PRODUCTS SECTION -->
            <div id="all-beauty-products" class="mt-12 md:mt-20 scroll-mt-24" x-data="{ selectedBeautyCategory: 'all' }">
                <div class="text-center mb-6 md:mb-8">
                    <h2 class="text-2xl md:text-4xl font-bold text-purple-900 mb-3 md:mb-4">All Beauty Products</h2>
                    <p class="text-sm md:text-base text-gray-600 max-w-2xl mx-auto px-4">Browse our complete collection of natural beauty products</p>
                </div>

                <!-- Category Filter -->
                <div class="mb-6 md:mb-8 overflow-x-auto scrollbar-hide">
                    <div class="flex flex-nowrap md:flex-wrap justify-start md:justify-center gap-2 md:gap-3 px-4 md:px-0 min-w-max md:min-w-0">
                    <button @click="selectedBeautyCategory = 'all'"
                        :class="selectedBeautyCategory === 'all' ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        class="px-4 md:px-6 py-2 md:py-2.5 rounded-full text-sm md:text-base font-semibold transition-all duration-300 border-2 border-transparent hover:border-purple-600">
                        All Products
                    </button>
                    @foreach($beautyCategories as $category)
                        <button @click="selectedBeautyCategory = '{{ $category->slug }}'"
                            :class="selectedBeautyCategory === '{{ $category->slug }}' ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg' : 'bg-white text-gray-700 hover:bg-gray-50'"
                            class="px-4 md:px-6 py-2 md:py-2.5 rounded-full text-sm md:text-base font-semibold transition-all duration-300 border-2 border-transparent hover:border-purple-600">
                            {{ $category->name }}
                        </button>
                    @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                    @foreach(App\Models\Product::where('type', 'beauty')->with(['images', 'category'])->get() as $product)
                        <a href="/shop/product/{{ $product->slug }}" class="group"
                            x-show="selectedBeautyCategory === 'all' || selectedBeautyCategory === '{{ $product->category?->slug }}'"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95">
                            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                                <div class="relative aspect-square overflow-hidden bg-gray-100">
                                    @if($product->images->first())
                                        <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-purple-50 to-pink-100 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-purple-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 2a8 8 0 100 16 8 8 0 000-16z" />
                                            </svg>
                                        </div>
                                    @endif
                                    @if($product->category)
                                        <div class="absolute top-2 md:top-3 left-2 md:left-3 bg-white/90 backdrop-blur-sm px-2 md:px-3 py-0.5 md:py-1 rounded-full text-xs font-semibold text-purple-800">
                                            {{ $product->category->name }}
                                        </div>
                                    @endif
                                </div>
                                <div class="p-3 md:p-4">
                                    <h3 class="font-semibold text-sm md:text-base text-gray-900 mb-1 md:mb-2 group-hover:text-purple-700 transition-colors">
                                        {{ $product->name }}
                                    </h3>
                                    <p class="text-purple-700 font-bold text-base md:text-lg">{{ number_format((float)$product->price, 0) }} DA</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

    <!-- Side Cart -->
    <div x-show="cartOpen" @click.away="cartOpen = false" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed top-0 right-0 h-full w-[85%] sm:w-96 bg-white shadow-2xl z-50 flex flex-col" style="display: none;">

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
                <div class="flex gap-3 md:gap-4 mb-3 md:mb-4 p-3 md:p-4 border rounded-lg hover:shadow-md transition group">
                    <a :href="'/shop/product/' + item.slug" class="flex-shrink-0">
                        <img :src="item.image" :alt="item.name" class="w-16 h-16 md:w-20 md:h-20 object-cover rounded hover:opacity-80 transition">
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
                            <button @click.stop="removeFromCart(item.id)" class="ml-auto text-red-500 hover:text-red-700 transition">
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
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center pt-20" style="display: none;">
        <div @click.stop class="bg-white rounded-lg shadow-2xl w-full max-w-2xl mx-4">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" x-model="searchQuery" @input="performSearch" placeholder="Search for products..."
                        class="flex-1 text-lg outline-none" autofocus>
                    <button @click="searchOpen = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
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
                        <a :href="`/shop/product/${result.slug}`"
                            class="flex gap-4 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition">
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

    @include('partials.login-modal')

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
        transform: translateX(400px);
        transition: transform 0.3s ease;
    ">
        <p id="notification-message" style="margin: 0; color: #1f2937; font-weight: 500;"></p>
    </div>

    <!-- Confirmation Modal -->
    <div x-show="showConfirmModal" 
         x-cloak
         @click.away="showConfirmModal = false"
         class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-[60] flex items-center justify-center"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.stop 
             class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-red-600 to-red-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
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
                <p class="text-gray-700 text-lg mb-6">Are you sure you want to empty your cart? All items will be removed.</p>
                
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
        #toast-notification.show {
            transform: translateX(0);
        }
    </style>

    <script>
        function shopStore() {
            return {
                currentMode: 'spice',
                cartOpen: false,
                searchOpen: false,
                showConfirmModal: false,
                cartItems: @js(array_values(session('cart', []))),
                searchQuery: '',
                searchResults: [],
                searchTimeout: null,

                switchMode(mode) {
                    this.currentMode = mode;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

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
                        this.showNotification('Item removed from cart', 'success');
                    })
                    .catch(error => {
                        console.error('Error removing item:', error);
                        this.showNotification('Failed to remove item', 'error');
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
                        this.showNotification('Cart emptied successfully', 'success');
                    })
                    .catch(error => {
                        console.error('Error clearing cart:', error);
                        this.showNotification('Failed to empty cart', 'error');
                    });
                },

                addToCart(product) {
                    const formData = new FormData();
                    formData.append('product_id', product.id);
                    formData.append('price', product.price);
                    formData.append('quantity', 1);

                    fetch('/cart/add', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            console.log('Cart updated:', data);
                            if (data.status === 'success' && data.item) {
                                const existingIdx = this.cartItems.findIndex(i => i.id == data.item.id);
                                if (existingIdx > -1) {
                                    this.cartItems[existingIdx].quantity = parseInt(data.item.quantity);
                                } else {
                                    this.cartItems.push(data.item);
                                }
                                this.cartOpen = true;
                                this.showNotification('تمت إضافة المنتج إلى السلة', 'success');
                            }
                        })
                        .catch(error => {
                            console.error('Cart update failed:', error);
                            this.showNotification('فشل تحديث السلة', 'error');
                        });
                },

                addToWishlist(id) {
                    fetch('/wishlist/add', {
                        method: 'POST',
                        body: JSON.stringify({ product_id: id }),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                        .then(res => {
                            if (res.status === 401) {
                                window.dispatchEvent(new CustomEvent('open-login-modal'));
                                return null;
                            }
                            return res.json();
                        })
                        .then(data => {
                            if (data) {
                                this.showNotification(data.message, data.status === 'success' ? 'success' : 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Only show error if it wasn't a 401 (which returns null)
                            if (error) {
                                this.showNotification('حدث خطأ أثناء إضافة المنتج', 'error');
                            }
                        });
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
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.style.display = 'none';
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