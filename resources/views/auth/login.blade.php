<x-guest-layout>
    <div style="min-height: 100vh; display: flex; margin: 0; padding: 0;">
        <!-- Left Side: Image -->
        <div style="width: 50%; background: linear-gradient(135deg, rgba(139, 58, 58, 0.9), rgba(114, 47, 55, 0.9)), url(/images/EpiArt-story.png) center/cover; color: white; display: flex; flex-direction: column; justify-content: flex-end; padding: 3rem; margin: 0;">
            <a href="/" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none; color: white; margin-bottom: 2rem;">
                <img src="/logo/logo-main.png" alt="EpiArt" style="width: 80px; height: 80px;">
                <span style="font-size: 2rem; font-weight: bold;">EpiArt</span>
            </a>
            <h2 style="font-size: 2.25rem; font-weight: bold; margin-bottom: 1rem;">Welcome Back</h2>
            <p style="font-size: 1.125rem; color: #fef3c7; max-width: 28rem; line-height: 1.6;">Sign in to access your account and explore our premium spice collection. Discover authentic flavors from around the world.</p>
        </div>

        <!-- Right Side: Form -->
        <div style="width: 50%; display: flex; flex-direction: column; justify-content: center; padding: 2rem; background: #fafafa; overflow-y: auto; margin: 0;">
            <div style="width: 100%; max-width: 28rem; margin: 0 auto;">
                <div style="text-align: center; margin-bottom: 2.5rem;">
                    <a href="/" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; margin-bottom: 1.5rem;">
                        <img src="/logo/logo-main.png" alt="EpiArt" style="height: 40px;">
                        <span style="font-size: 1.5rem; font-weight: bold; color: #8B3A3A;">EpiArt</span>
                    </a>
                    <h2 style="font-size: 1.875rem; font-weight: 700; color: #8B3A3A; margin: 0; margin-top: 0.5rem;">Sign In</h2>
                    <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">Access your account to continue shopping</p>
                </div>

                <!-- Session Status -->
                @if ($errors->any())
                    <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 6px; padding: 1rem; margin-bottom: 1rem;">
                        @foreach ($errors->all() as $error)
                            <p style="color: #dc2626; font-size: 0.875rem;">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 1rem; box-sizing: border-box;">
                        @error('email') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 1rem; box-sizing: border-box;">
                        @error('password') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <label style="display: flex; align-items: center;">
                            <input type="checkbox" name="remember" style="width: 1rem; height: 1rem; border: 1px solid #e5e7eb; border-radius: 4px; cursor: pointer;">
                            <span style="margin-left: 0.5rem; font-size: 0.875rem; color: #6b7280;">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="font-size: 0.875rem; color: #d97706; text-decoration: none; font-weight: 600;">Forgot password?</a>
                        @endif
                    </div>

                    <button type="submit" style="width: 100%; padding: 0.75rem; background: #8B3A3A; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s; font-size: 1rem;">
                        Sign In
                    </button>

                    <div style="text-align: center;">
                        <span style="color: #6b7280; font-size: 0.875rem;">Don't have an account?</span>
                        <a href="{{ route('register') }}" style="color: #8B3A3A; text-decoration: none; font-weight: 600; margin-left: 0.25rem;">Sign up</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
