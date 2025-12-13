<x-guest-layout>
    <div class="min-h-screen flex">
        <!-- Left Side: Image -->
        <div class="hidden lg:block w-1/2 relative">
            <img src="{{ asset('images/EpiArt-story.png') }}" alt="Spices" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-red-800/50"></div>
            <div class="absolute bottom-0 left-0 p-12 text-white">
                <h2 class="text-4xl font-serif font-bold mb-4">Taste the Tradition</h2>
                <p class="text-lg text-white max-w-md">Experience the authentic flavors of Algeria with our premium spice collection.</p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 md:px-16 lg:px-24 bg-white">
            <div class="w-full max-w-md mx-auto">
                <div class="text-center mb-10">
                    <a href="/">
                        <img src="{{ asset('images/EpiArt-logo-transparent.png') }}" alt="EpiArt" class="h-20 mx-auto">
                    </a>
                    <h2 class="mt-6 text-2xl font-bold text-gray-900 font-serif">Welcome Back</h2>
                    <p class="mt-2 text-sm text-gray-600">Please sign in to your account</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full rounded-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="block mt-1 w-full rounded-full" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-red-600 hover:text-red-800 font-medium" href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <button class="w-full justify-center bg-red-800 hover:bg-red-900 text-white font-bold py-3 rounded-full shadow-lg transition transform hover:-translate-y-0.5">
                        {{ __('Log in') }}
                    </button>

                    <p class="text-center text-sm text-gray-600 mt-4">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-red-600 hover:text-red-800 font-bold">Sign up</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
