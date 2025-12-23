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
                <h2 class="text-3xl lg:text-4xl font-bold mb-4">Join Our Community</h2>
                <p class="text-base lg:text-lg text-yellow-100 max-w-md leading-relaxed">Create your account and start
                    enjoying premium spices with exclusive member benefits. Become part of the EpiArt family.</p>
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
                    <h2 class="text-2xl md:text-3xl font-bold text-[#8B3A3A] mt-2">Create Account</h2>
                    <p class="mt-2 text-sm text-gray-600">Fill in your details to get started</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4 md:gap-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700 text-sm md:text-base">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                            class="w-full px-3 md:px-4 py-3 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-[#8B3A3A] focus:border-transparent transition">
                        @error('name') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700 text-sm md:text-base">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                            class="w-full px-3 md:px-4 py-3 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-[#8B3A3A] focus:border-transparent transition">
                        @error('email') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700 text-sm md:text-base">Password</label>
                        <input type="password" name="password" required autocomplete="new-password"
                            class="w-full px-3 md:px-4 py-3 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-[#8B3A3A] focus:border-transparent transition">
                        @error('password') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700 text-sm md:text-base">Confirm
                            Password</label>
                        <input type="password" name="password_confirmation" required autocomplete="new-password"
                            class="w-full px-3 md:px-4 py-3 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-[#8B3A3A] focus:border-transparent transition">
                        @error('password_confirmation') <span
                        class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit"
                        class="w-full py-3 md:py-3.5 bg-[#8B3A3A] text-white border-none rounded-lg font-semibold cursor-pointer transition-all hover:bg-[#722F37] text-base md:text-lg shadow-md hover:shadow-lg mt-2">
                        Create Account
                    </button>

                    <div class="text-center">
                        <span class="text-gray-600 text-sm">Already have an account?</span>
                        <a href="{{ route('login') }}"
                            class="text-[#8B3A3A] no-underline font-semibold ml-1 hover:text-[#722F37]">Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>