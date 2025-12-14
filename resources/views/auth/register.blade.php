<x-guest-layout>
    <div style="min-height: 100vh; display: flex; margin: 0; padding: 0;">
        <!-- Left Side: Image -->
        <div style="width: 50%; background: linear-gradient(135deg, rgba(139, 58, 58, 0.9), rgba(114, 47, 55, 0.9)), url(/images/EpiArt-story.png) center/cover; color: white; display: flex; flex-direction: column; justify-content: flex-end; padding: 3rem; margin: 0;">
            <a href="/" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none; color: white; margin-bottom: 2rem;">
                <img src="/images/logo.png" alt="EpiArt" style="width: 80px; height: 80px;">
                <span style="font-size: 2rem; font-weight: bold;">EpiArt</span>
            </a>
            <h2 style="font-size: 2.25rem; font-weight: bold; margin-bottom: 1rem;">Join Our Community</h2>
            <p style="font-size: 1.125rem; color: #fef3c7; max-width: 28rem; line-height: 1.6;">Create your account and start enjoying premium spices with exclusive member benefits. Become part of the EpiArt family.</p>
        </div>

        <!-- Right Side: Form -->
        <div style="width: 50%; display: flex; flex-direction: column; justify-content: center; padding: 2rem; background: #fafafa; overflow-y: auto; margin: 0;">
            <div style="width: 100%; max-width: 28rem; margin: 0 auto;">
                <div style="text-align: center; margin-bottom: 2.5rem;">
                    <a href="/" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; margin-bottom: 1.5rem;">
                        <img src="/images/logo.png" alt="EpiArt" style="height: 40px;">
                        <span style="font-size: 1.5rem; font-weight: bold; color: #8B3A3A;">EpiArt</span>
                    </a>
                    <h2 style="font-size: 1.875rem; font-weight: 700; color: #8B3A3A; margin: 0; margin-top: 0.5rem;">Create Account</h2>
                    <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">Fill in your details to get started</p>
                </div>

                <form method="POST" action="{{ route('register') }}" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 1rem; box-sizing: border-box;">
                        @error('name') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 1rem; box-sizing: border-box;">
                        @error('email') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Password</label>
                        <input type="password" name="password" required autocomplete="new-password" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 1rem; box-sizing: border-box;">
                        @error('password') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">Confirm Password</label>
                        <input type="password" name="password_confirmation" required autocomplete="new-password" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 1rem; box-sizing: border-box;">
                        @error('password_confirmation') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" style="width: 100%; padding: 0.75rem; background: #8B3A3A; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s; font-size: 1rem;">
                        Create Account
                    </button>

                    <div style="text-align: center;">
                        <span style="color: #6b7280; font-size: 0.875rem;">Already have an account?</span>
                        <a href="{{ route('login') }}" style="color: #8B3A3A; text-decoration: none; font-weight: 600; margin-left: 0.25rem;">Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
