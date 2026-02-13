@extends('layouts.auth')

@section('title', 'Reset Password - SAFEGEST')

@section('content')
<div class="bg-white rounded-2xl shadow-lg p-8">
    <h2 class="text-2xl font-semibold text-gray-800 mb-2">{{ __('lang.reset_password') }}</h2>
    <p class="text-gray-500 text-sm mb-8">{{ __('lang.reset_password_instructions') }}</p>

    @if (session('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-600">{{ session('status') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <ul class="text-sm text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Input -->
        <div class="mb-6">
            <div class="relative">
                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </span>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="{{ __('lang.email_address') }}"
                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                    required
                    autofocus
                >
            </div>
        </div>

        <!-- Submit Button -->
        <button
            type="button"
            class="w-full bg-blue-900 text-white py-3 rounded-lg font-semibold hover:bg-blue-800 transition duration-200 shadow-md mb-4"
        >
            {{ __('lang.send_reset_link') }}
        </button>

        <!-- Back to Login -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">{{ __('lang.back_to_login') }}</a>
        </div>
    </form>
</div>
@endsection
