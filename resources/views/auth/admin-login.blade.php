<x-guest-layout>
    <div style="min-height: 100vh; display: flex; margin: 0; padding: 0;">
        <!-- Left Side: Image -->
        <div style="width: 50%; background: linear-gradient(135deg, rgba(139, 58, 58, 0.9), rgba(114, 47, 55, 0.9)), url(/images/EpiArt-story.png) center/cover; color: white; display: flex; flex-direction: column; justify-content: flex-end; padding: 3rem; margin: 0;">
            <img src="/images/EpiArt-logo.png" alt="EpiArt" style="width: 80px; height: 80px; margin-bottom: 2rem;">
            <h2 style="font-size: 2.25rem; font-weight: bold; margin-bottom: 1rem;">Admin Portal</h2>
            <p style="font-size: 1.125rem; color: #fef3c7; max-width: 28rem; line-height: 1.6;">Manage your EpiArt store, products, orders, and customers from our admin dashboard.</p>
        </div>

        <!-- Right Side: Form -->
        <div style="width: 50%; display: flex; flex-direction: column; justify-content: center; padding: 2rem; background: #fafafa; overflow-y: auto; margin: 0;">
            <div style="width: 100%; max-width: 28rem; margin: 0 auto;">
                <div style="text-align: center; margin-bottom: 2.5rem;">
                    <img src="/images/EpiArt-horizontal.png" alt="EpiArt" style="height: 50px; margin-bottom: 1.5rem;">
                    <h2 style="font-size: 1.875rem; font-weight: 700; color: #8B3A3A; margin: 0; margin-top: 0.5rem;">Admin Sign In</h2>
                    <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">Access the admin dashboard</p>
                </div>

                <!-- Session Status -->
                @if ($errors->any())
                    <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 6px; padding: 1rem; margin-bottom: 1rem;">
                        @foreach ($errors->all() as $error)
                            <p style="color: #dc2626; font-size: 0.875rem;">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.store') }}" style="display: flex; flex-direction: column; gap: 1.5rem;">
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

                    <button type="submit" style="width: 100%; padding: 0.75rem; background: #8B3A3A; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s; font-size: 1rem;">
                        Sign In to Admin Panel
                    </button>

                    <div style="text-align: center;">
                        <span style="color: #6b7280; font-size: 0.875rem;">Not an admin?</span>
                        <a href="{{ route('login') }}" style="color: #8B3A3A; text-decoration: none; font-weight: 600; margin-left: 0.25rem;">Customer Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
