<x-guest-layout>
    <div class="min-h-screen flex m-0 p-0">
        <!-- Left Side: Image (Hidden on Mobile) -->
        <div
            class="hidden md:flex md:w-1/2 bg-gradient-to-br from-[#8B3A3A]/90 to-[#722F37]/90 text-white flex-col justify-end p-8 lg:p-12 m-0 relative">
            <div class="absolute inset-0 bg-cover bg-center"
                style="background-image: url(/images/EpiArt-story.png); z-index: -1;"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-[#8B3A3A]/90 to-[#722F37]/90" style="z-index: 0;"></div>

            <div class="relative z-10">
                <a href="/" class="flex items-center gap-3 text-white no-underline mb-8">
                    <img src="/logo/logo-main.png" alt="EpiArt" class="w-16 h-16 lg:w-20 lg:h-20">
                    <span class="text-3xl lg:text-4xl font-bold">EpiArt</span>
                </a>
                <h2 class="text-3xl lg:text-4xl font-bold mb-4">Welcome Back</h2>
                <p class="text-base lg:text-lg text-yellow-100 max-w-md leading-relaxed">Sign in to access your account
                    and explore our premium spice collection. Discover authentic flavors from around the world.</p>
            </div>
        </div>

        <!-- Right Side: Form (Full Width on Mobile) -->
        <div class="w-full md:w-1/2 flex flex-col justify-center p-4 sm:p-6 md:p-8 bg-gray-50 overflow-y-auto m-0">
            <div class="w-full max-w-md mx-auto">
                <!-- Mobile Logo (Visible only on mobile) -->
                <div class="md:hidden text-center mb-6">
                    <a href="/" class="inline-flex items-center gap-2 no-underline">
                        <img src="/logo/logo-main.png" alt="EpiArt" class="h-12">
                        <span class="text-2xl font-bold text-[#8B3A3A]">EpiArt</span>
                    </a>
                </div>

                <div class="text-center mb-8 md:mb-10">
                    <a href="/" class="hidden md:inline-flex items-center gap-2 no-underline mb-6">
                        <img src="/logo/logo-main.png" alt="EpiArt" class="h-10">
                        <span class="text-2xl font-bold text-[#8B3A3A]">EpiArt</span>
                    </a>
                    <h2 class="text-2xl md:text-3xl font-bold text-[#8B3A3A] mt-2">Sign In</h2>
                    <p class="mt-2 text-sm text-gray-600">Access your account to continue shopping</p>
                </div>

                <!-- Session Status -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 md:p-4 mb-4">
                        @foreach ($errors->all() as $error)
                            <p class="text-red-600 text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700 text-sm md:text-base">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            autocomplete="username"
                            class="w-full px-3 md:px-4 py-3 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-[#8B3A3A] focus:border-transparent transition">
                        @error('email') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700 text-sm md:text-base">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full px-3 md:px-4 py-3 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-[#8B3A3A] focus:border-transparent transition">
                        @error('password') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 border-gray-300 rounded cursor-pointer text-[#8B3A3A] focus:ring-[#8B3A3A]">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-amber-600 no-underline font-semibold hover:text-amber-700">Forgot
                                password?</a>
                        @endif
                    </div>

                    <button type="submit"
                        class="w-full py-3 md:py-3.5 bg-[#8B3A3A] text-white border-none rounded-lg font-semibold cursor-pointer transition-all hover:bg-[#722F37] text-base md:text-lg shadow-md hover:shadow-lg">
                        Sign In
                    </button>

                    <div class="text-center">
                        <span class="text-gray-600 text-sm">Don't have an account?</span>
                        <a href="{{ route('register') }}"
                            class="text-[#8B3A3A] no-underline font-semibold ml-1 hover:text-[#722F37]">Sign up</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>