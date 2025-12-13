<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EpiArt - Spices & Beauty</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|playfair-display:600,700" rel="stylesheet" />

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
                                50: '#fdf2f2',
                                100: '#fde8e8',
                                500: '#ef4444',
                                600: '#dc2626',
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
        [x-cloak] { display: none !important; }

        .fade-enter-active, .fade-leave-active {
            transition: opacity 0.5s ease;
        }
        .fade-enter-from, .fade-leave-to {
            opacity: 0;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-white" x-data="shopStore()">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-colors duration-300"
         :class="currentMode === 'spice' ? 'bg-white/90 backdrop-blur-md border-b border-orange-100' : 'bg-white/90 backdrop-blur-md border-b border-pink-100'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer" @click="currentMode = 'spice'">
                    <span class="font-serif text-3xl font-bold tracking-tight transition-colors duration-500"
                          :class="currentMode === 'spice' ? 'text-spice-800' : 'text-beauty-800'">
                        EpiArt
                    </span>
                </div>

                <!-- Mode Switcher (Central Toggle) -->
                <div class="hidden md:flex bg-gray-100 p-1 rounded-full relative">
                    <div class="w-24 h-8 bg-white rounded-full shadow-sm absolute transition-all duration-300 ease-out top-1"
                         :class="currentMode === 'spice' ? 'left-1' : 'left-[6.25rem]'"></div>

                    <button @click="switchMode('spice')"
                            class="relative z-10 w-24 h-8 rounded-full text-sm font-semibold transition-colors duration-300 focus:outline-none"
                            :class="currentMode === 'spice' ? 'text-spice-800' : 'text-gray-500 hover:text-gray-700'">
                        Spices
                    </button>
                    <button @click="switchMode('beauty')"
                            class="relative z-10 w-24 h-8 rounded-full text-sm font-semibold transition-colors duration-300 focus:outline-none"
                            :class="currentMode === 'beauty' ? 'text-beauty-800' : 'text-gray-500 hover:text-gray-700'">
                        Beauty
                    </button>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center space-x-6">
                    <a href="#" class="text-sm font-medium hover:underline transition-colors"
                       :class="currentMode === 'spice' ? 'text-spice-800 hover:text-spice-600' : 'text-beauty-800 hover:text-beauty-600'">
                        Search
                    </a>
                    <a href="#" class="text-sm font-medium hover:underline transition-colors"
                       :class="currentMode === 'spice' ? 'text-spice-800 hover:text-spice-600' : 'text-beauty-800 hover:text-beauty-600'">
                        Cart (0)
                    </a>

                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-full text-white text-sm font-medium transition-colors shadow-md"
                           :class="currentMode === 'spice' ? 'bg-spice-800 hover:bg-spice-900' : 'bg-beauty-800 hover:bg-beauty-900'">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium hover:underline transition-colors mr-4"
                           :class="currentMode === 'spice' ? 'text-spice-800 hover:text-spice-600' : 'text-beauty-800 hover:text-beauty-600'">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-full text-white text-sm font-medium transition-colors shadow-md"
                           :class="currentMode === 'spice' ? 'bg-spice-800 hover:bg-spice-900' : 'bg-beauty-800 hover:bg-beauty-900'">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Toggle (Visible only on small screens) -->
        <div class="md:hidden flex border-t border-gray-100">
            <button @click="switchMode('spice')" class="flex-1 py-3 text-sm font-medium text-center transition-colors"
                :class="currentMode === 'spice' ? 'bg-spice-50 text-spice-800' : 'bg-white text-gray-500'">
                Spices
            </button>
            <button @click="switchMode('beauty')" class="flex-1 py-3 text-sm font-medium text-center transition-colors"
                :class="currentMode === 'beauty' ? 'bg-beauty-50 text-beauty-800' : 'bg-white text-gray-500'">
                Beauty
            </button>
        </div>
    </nav>

    <!-- Full Screen Hero Slider -->
    <div class="relative h-screen w-full overflow-hidden top-0 left-0">

        <!-- Spices Hero -->
        <div class="absolute inset-0 transition-opacity duration-700 ease-in-out"
             x-show="currentMode === 'spice'"
             x-transition.opacity.duration.700ms>

            <img src="https://images.unsplash.com/photo-1596040033229-a9821ebd058d?q=80&w=2070&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover" alt="Spices Hero">
            <div class="absolute inset-0 bg-black/40"></div>

            <div class="relative z-10 h-full flex flex-col justify-center items-center text-center text-white px-4 pt-20">
                <span class="uppercase tracking-[0.2em] text-sm mb-4 font-semibold text-orange-200">Authentic Flavors</span>
                <h1 class="font-serif text-5xl md:text-7xl lg:text-8xl mb-6">Taste The Tradition</h1>
                <p class="text-lg md:text-xl text-gray-200 max-w-2xl mb-10">
                    Hand-picked saffron, aromatic cardamoms, and rare blends from the silk road to your kitchen.
                </p>
                <button class="px-8 py-4 bg-white text-spice-900 font-bold rounded-none hover:bg-orange-50 transition transform hover:scale-105">
                    EXPLORE SPICES
                </button>
            </div>
        </div>

        <!-- Beauty Hero -->
        <div class="absolute inset-0 transition-opacity duration-700 ease-in-out"
             x-show="currentMode === 'beauty'"
             style="display: none;"
             x-transition.opacity.duration.700ms>

            <img src="https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?q=80&w=2070&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover" alt="Beauty Hero">
            <div class="absolute inset-0 bg-black/30"></div>

            <div class="relative z-10 h-full flex flex-col justify-center items-center text-center text-white px-4 pt-20">
                <span class="uppercase tracking-[0.2em] text-sm mb-4 font-semibold text-pink-200">Pure & Organic</span>
                <h1 class="font-serif text-5xl md:text-7xl lg:text-8xl mb-6">Radiant Natural Beauty</h1>
                <p class="text-lg md:text-xl text-gray-200 max-w-2xl mb-10">
                    Rejuvenating serums, organic clays, and floral essences crafted for your glow.
                </p>
                <button class="px-8 py-4 bg-white text-beauty-900 font-bold rounded-none hover:bg-pink-50 transition transform hover:scale-105">
                    DISCOVER BEAUTY
                </button>
            </div>
        </div>
    </div>

    <!-- Dynamic Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 min-h-[500px]">

        <!-- SPICE CONTENT -->
        <div x-show="currentMode === 'spice'" x-transition.opacity.duration.500ms>
            <div class="text-center mb-16">
                <h2 class="text-3xl font-serif font-bold text-spice-900 mb-4">Curated Spice Collections</h2>
                <div class="h-1 w-20 bg-spice-500 mx-auto"></div>
            </div>

                                                            <!-- Categories Grid (Pyramid Layout - Robust Grid) -->

                                                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 items-end mb-20 px-4">



                                                                <!-- 1. Cereals (Small) -->

                                                                <div class="group cursor-pointer transform lg:scale-75 transition duration-500 hover:-translate-y-2 origin-bottom">

                                                                    <div class="relative aspect-square overflow-hidden mb-3 rounded-lg shadow-sm">

                                                                        <img src="https://images.unsplash.com/photo-1574315042621-50c76891eb66?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Cereals">

                                                                        <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition"></div>

                                                                    </div>

                                                                    <h3 class="text-base font-bold text-center text-gray-900 group-hover:text-spice-600 transition">Cereals</h3>

                                                                </div>



                                                                <!-- 2. Mix (Medium) -->

                                                                <div class="group cursor-pointer transform lg:scale-90 transition duration-500 hover:-translate-y-2 origin-bottom">

                                                                    <div class="relative aspect-square overflow-hidden mb-3 rounded-lg shadow-md">

                                                                        <img src="https://images.unsplash.com/photo-1532336414038-cf00d472c914?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Mix">

                                                                        <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition"></div>

                                                                    </div>

                                                                    <h3 class="text-lg font-bold text-center text-gray-900 group-hover:text-spice-600 transition">Mix</h3>

                                                                </div>



                                                                <!-- 3. Basics (Large - Center) -->

                                                                <div class="group cursor-pointer transform lg:scale-110 z-10 transition duration-500 hover:-translate-y-2 origin-bottom">

                                                                    <div class="relative aspect-square overflow-hidden mb-4 rounded-xl shadow-xl border-4 border-white">

                                                                        <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Basics">

                                                                        <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition"></div>

                                                                    </div>

                                                                    <h3 class="text-2xl font-bold text-center text-spice-800 group-hover:text-spice-600 transition uppercase tracking-widest">Basics</h3>

                                                                </div>



                                                                <!-- 4. Coffee & Tea (Medium) -->

                                                                <div class="group cursor-pointer transform lg:scale-90 transition duration-500 hover:-translate-y-2 origin-bottom">

                                                                    <div class="relative aspect-square overflow-hidden mb-3 rounded-lg shadow-md">

                                                                        <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Coffee & Tea">

                                                                        <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition"></div>

                                                                    </div>

                                                                    <h3 class="text-lg font-bold text-center text-gray-900 group-hover:text-spice-600 transition">Coffee & Tea</h3>

                                                                </div>



                                                                <!-- 5. Oils (Small) -->

                                                                <div class="group cursor-pointer transform lg:scale-75 transition duration-500 hover:-translate-y-2 origin-bottom">

                                                                    <div class="relative aspect-square overflow-hidden mb-3 rounded-lg shadow-sm">

                                                                        <img src="https://images.unsplash.com/photo-1519624027794-272cb831c3c9?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Oils">

                                                                        <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition"></div>

                                                                    </div>

                                                                    <h3 class="text-base font-bold text-center text-gray-900 group-hover:text-spice-600 transition">Oils</h3>

                                                                </div>



                                                            </div>            <!-- New Arrivals -->
            <div class="text-center mb-12">
                <h3 class="text-2xl font-bold text-gray-800">New in the Kitchen</h3>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Product Card -->
                <template x-for="i in 4">
                    <div class="bg-gray-50 p-4 rounded-lg hover:shadow-lg transition">
                        <div class="aspect-square bg-white mb-4 flex items-center justify-center text-spice-200">
                            <!-- Placeholder Icon -->
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/></svg>
                        </div>
                        <h4 class="font-semibold text-gray-900">Premium Saffron</h4>
                        <p class="text-spice-600 font-bold mt-1">$45.00</p>
                    </div>
                </template>
            </div>
        </div>

        <!-- BEAUTY CONTENT -->
        <div x-show="currentMode === 'beauty'" x-transition.opacity.duration.500ms style="display: none;">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-serif font-bold text-beauty-900 mb-4">Pure Beauty Collections</h2>
                <div class="h-1 w-20 bg-beauty-500 mx-auto"></div>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
                <div class="group cursor-pointer">
                    <div class="relative aspect-[3/4] overflow-hidden mb-4 rounded-full border-4 border-beauty-50">
                        <img src="https://placehold.co/600x800/86198f/white?text=Skincare" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                    </div>
                    <h3 class="text-xl font-bold text-center text-gray-900 group-hover:text-beauty-600 transition">Organic Skincare</h3>
                </div>
                <div class="group cursor-pointer">
                    <div class="relative aspect-[3/4] overflow-hidden mb-4 rounded-full border-4 border-beauty-50">
                        <img src="https://placehold.co/600x800/d946ef/white?text=Haircare" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                    </div>
                    <h3 class="text-xl font-bold text-center text-gray-900 group-hover:text-beauty-600 transition">Hair Wellness</h3>
                </div>
                <div class="group cursor-pointer">
                    <div class="relative aspect-[3/4] overflow-hidden mb-4 rounded-full border-4 border-beauty-50">
                        <img src="https://placehold.co/600x800/701a75/white?text=Aromatherapy" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                    </div>
                    <h3 class="text-xl font-bold text-center text-gray-900 group-hover:text-beauty-600 transition">Aromatherapy</h3>
                </div>
            </div>

             <!-- New Arrivals -->
             <div class="text-center mb-12">
                <h3 class="text-2xl font-bold text-gray-800">Fresh for Your Routine</h3>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Product Card -->
                <template x-for="i in 4">
                    <div class="bg-beauty-50 p-4 rounded-xl hover:shadow-lg transition border border-beauty-100">
                        <div class="aspect-square bg-white rounded-lg mb-4 flex items-center justify-center text-beauty-200">
                            <!-- Placeholder Icon -->
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/></svg>
                        </div>
                        <h4 class="font-semibold text-gray-900">Rose Facial Oil</h4>
                        <p class="text-beauty-600 font-bold mt-1">$28.00</p>
                    </div>
                </template>
            </div>
        </div>

    </div>

    <script>
        function shopStore() {
            return {
                currentMode: 'spice', // 'spice' or 'beauty'

                switchMode(mode) {
                    this.currentMode = mode;
                    // Optional: Smooth scroll to top when switching?
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            }
        }
    </script>
</body>
</html>
