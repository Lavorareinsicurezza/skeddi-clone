@extends('layouts.auth')

@section('title', 'Login - SAFEGEST')

@section('content')
    <div class="w-full max-w-xl items-center">
        <h1 class="text-5xl font-bold text-[#0C3183] pb-8 text-center">{{ __('lang.management_software' )}}</h1>

        <div class="bg-white rounded-2xl max-w-lg ml-[30px] shadow-lg p-8">
            <h2 class="text-4xl font-bold text-gray-900 mb-2 text-center">{{ __('lang.login_to_your_account' )}}</h2>
            <p class="text-gray-600 text-sm text-center mb-8">{{ __('lang.login_instructions') }}</p>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm text-green-600">{{ session('success') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Input -->
                <div class="mb-4">
                    <div class="relative">
                        <span class="absolute left-5 top-6 -translate-y-1/2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('lang.email_address') }}"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            required autofocus>
                    </div>
                </div>

                <!-- Password Input -->
                <div class="mb-4">
                    <div class="relative">
                        <span class="absolute left-5 top-6 -translate-y-1/2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input type="password" name="password" id="password" placeholder="{{ __('lang.password') }}"
                            class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                            required>
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-5 top-6 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember"
                            class="w-4 h-4 text-blue-600 border border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">{{ __('lang.remember_me') }}</span>
                    </label>
                    {{-- <a href="{{ route('password.request') }}"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">{{ __('lang.forgot_password') }}</a> --}}
                </div>

                <!-- Login Button -->
                <button type="submit"
                    class="w-full bg-blue-900 text-white py-3 rounded-lg font-semibold hover:bg-blue-800 transition duration-200 shadow-md">
                    {{ __('lang.login') }}
                </button>

                <!-- Sign Up & Contact Links -->
                <div class="mt-6 text-center space-y-2">
                    {{-- <p class="text-sm text-gray-600">
                        {{ __('lang.dont_have_an_account_for_your_company') }}
                        <a href="{{ route('register') }}"
                            class="text-blue-600 hover:text-blue-800 font-medium">{{ __('lang.register') }}</a>
                    </p> --}}
                    {{-- <p class="text-sm text-gray-600">
                        {{ __('lang.are_you_a_consultant_or_freelancer') }}
                        <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">{{ __('lang.contact_us') }}</a>
                    </p> --}}
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
@endsection
