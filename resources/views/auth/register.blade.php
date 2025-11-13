@extends('layouts.auth')

@section('title', __('lang.registration_page_title'))

@section('content')
    <div class="w-full max-w-2xl">
        <h1 class="text-5xl font-bold text-[#0C3183] mb-2 text-center">{{ __('lang.management_software') }}</h1>
        <div class="bg-white rounded-2xl shadow-lg px-12 py-4">
            <h2 class="text-4xl font-bold text-gray-900 mb-2 text-center">{{ __('lang.register_your_company') }}</h2>
            <p class="text-gray-600 text-sm text-center mb-8">{{ __('lang.signup_instructions') }}</p>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-2">
                @csrf

                <!-- Company Name and VAT Number -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <input type="text" name="company_name" value="{{ old('company_name') }}"
                            placeholder="{{ __('lang.company_name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('company_name') border-red-500 @enderror"
                            required>
                    </div>
                    <div>
                        <input type="text" name="vat_number" value="{{ old('vat_number') }}" placeholder="{{ __('lang.vat_number') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vat_number') border-red-500 @enderror"
                            required>
                    </div>
                </div>

                <!-- Email and Phone Number -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('lang.email_address') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            required>
                    </div>
                    <div>
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="{{ __('lang.phone_number') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                            required>
                    </div>
                </div>

                <!-- Legal Headquarters Section -->
                <div>
                    <label class="block text-gray-600 font-medium mb-3">{{ __('lang.legal_headquarters') }}</label>
                    <div class="grid grid-cols-4 gap-4">
                        <div>
                            <input type="text" name="legal_address_street" value="{{ old('legal_address_street') }}"
                                placeholder="{{ __('lang.street') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('legal_address_street') border-red-500 @enderror"
                                required>
                        </div>
                        <div>
                            <input type="text" name="legal_address_number" value="{{ old('legal_address_number') }}"
                                placeholder="{{ __('lang.no') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('legal_address_number') border-red-500 @enderror"
                                required>
                        </div>
                        <div>
                            <input type="text" name="legal_address_postal" value="{{ old('legal_address_postal') }}"
                                placeholder="{{ __('lang.postal_code') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('legal_address_postal') border-red-500 @enderror"
                                required>
                        </div>
                        <div>
                            <input type="text" name="legal_address_city" value="{{ old('legal_address_city') }}"
                                placeholder="{{ __('lang.city') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('legal_address_city') border-red-500 @enderror"
                                required>
                        </div>
                    </div>
                </div>

                <!-- Operating Headquarters Section -->
                <div>
                    <label class="block text-gray-600 font-medium mb-3">{{ __('lang.operating_headquarters') }}</label>
                    <div class="grid grid-cols-4 gap-4">
                        <div>
                            <input type="text" name="operating_address_street" value="{{ old('operating_address_street') }}"
                                placeholder="{{ __('lang.street') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('operating_address_street') border-red-500 @enderror"
                                required>
                        </div>
                        <div>
                            <input type="text" name="operating_address_number" value="{{ old('operating_address_number') }}"
                                placeholder="{{ __('lang.no') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('operating_address_number') border-red-500 @enderror"
                                required>
                        </div>
                        <div>
                            <input type="text" name="operating_address_postal" value="{{ old('operating_address_postal') }}"
                                placeholder="{{ __('lang.postal_code') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('operating_address_postal') border-red-500 @enderror"
                                required>
                        </div>
                        <div>
                            <input type="text" name="operating_address_city" value="{{ old('operating_address_city') }}"
                                placeholder="{{ __('lang.city') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('operating_address_city') border-red-500 @enderror"
                                required>
                        </div>
                    </div>
                </div>

                <!-- Owner Name -->
                <div>
                    <input type="text" name="owner_name" value="{{ old('owner_name') }}" placeholder="{{ __('lang.owner_name') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('owner_name') border-red-500 @enderror"
                        required>
                </div>

                <!-- Password Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <input type="password" name="password" placeholder="{{ __('lang.password') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                            required>
                    </div>
                    <div>
                        <input type="password" name="password_confirmation" placeholder="{{ __('lang.confirm_password') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                    </div>
                </div>

                <!-- Terms and Conditions Checkbox -->
                <div class="flex items-center mt-4">
                    <input type="checkbox" id="terms" name="terms"
                        class="w-4 h-4 text-blue-600 border border-gray-300 rounded focus:ring-blue-500 @error('terms') border-red-500 @enderror"
                        required>
                    <label for="terms" class="ml-2 text-sm text-gray-700">
                        {{ __('lang.i_accept_the') }} <a href="#" class="text-red-500 hover:text-red-700 font-medium">{{ __('lang.license_terms') }}</a>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 mt-6">
                    <a href="{{ route('login') }}"
                        class="px-12 py-3 border-2 border-red-500 text-red-500 rounded-lg font-semibold hover:bg-red-50 transition duration-200 text-center">
                        {{ __('lang.cancel') }}
                    </a>
                    <button type="submit"
                        class="flex-1 px-12 py-3 bg-[#0C3183] text-white rounded-lg font-semibold hover:bg-blue-800 transition duration-200">
                        {{ __('lang.submit_registration') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
