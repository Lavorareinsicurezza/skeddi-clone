@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-6">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.edit_operating_location') }}</h1>
                    <p class="text-gray-500 text-sm flex items-center gap-2 mt-1">
                        <i class="fa fa-map-marker-alt text-[#0C3183]"></i>
                        <span>{{ __('lang.update_operating_location_details') }}</span>
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.operating-locations.index') }}"
                        class="bg-white text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 shadow-sm border border-gray-200 transition-all flex items-center gap-2 text-sm">
                        <i class="fa fa-arrow-left"></i>
                        <span>{{ __('lang.back') }}</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <form action="{{ route('admin.operating-locations.update', $operatingLocation->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <!-- Location Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('lang.location_name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                           value="{{ old('name', $operatingLocation->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent"
                           placeholder="{{ __('lang.location_name_placeholder') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('lang.address_information') }}</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('lang.address') }}
                            </label>
                            <input type="text" name="address" id="address"
                                   value="{{ old('address', $operatingLocation->address) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent"
                                   placeholder="{{ __('lang.address') }}">
                        </div>
                    </div>
                </div>

                <!-- Site Contact Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('lang.site_contact_information') }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="site_contact_name" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('lang.contact_name') }}
                            </label>
                            <input type="text" name="site_contact_name" id="site_contact_name"
                                   value="{{ old('site_contact_name', $operatingLocation->site_contact_name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent"
                                   placeholder="{{ __('lang.contact_name_placeholder') }}">
                        </div>

                        <div>
                            <label for="site_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('lang.contact_phone') }}
                            </label>
                            <input type="text" name="site_contact_phone" id="site_contact_phone"
                                   value="{{ old('site_contact_phone', $operatingLocation->site_contact_phone) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent"
                                   placeholder="{{ __('lang.contact_phone_placeholder') }}">
                        </div>

                        <div>
                            <label for="site_contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('lang.contact_email') }}
                            </label>
                            <input type="email" name="site_contact_email" id="site_contact_email"
                                   value="{{ old('site_contact_email', $operatingLocation->site_contact_email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent"
                                   placeholder="{{ __('lang.contact_email_placeholder') }}">
                            @error('site_contact_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SMTP Configuration -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('lang.smtp_configuration') }}</h3>

                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('lang.smtp_profile') }}
                        </label>
                        <div class="flex items-center gap-3">
                            <select name="smtp_profile_id"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                                <option value="">-- {{ __('lang.select_smtp_profile') }} --</option>
                                @foreach($smtpProfiles as $profile)
                                    <option value="{{ $profile->id }}" {{ old('smtp_profile_id', $operatingLocation->smtp_profile_id) == $profile->id ? 'selected' : '' }}>
                                        {{ $profile->name }}
                                    </option>
                                @endforeach
                            </select>
                            @can('create smtp-profiles')
                            <a href="{{ route('admin.smtp-profiles.create') }}" target="_blank"
                                class="px-4 py-2 bg-[#0C3183] text-white rounded-md hover:bg-blue-800 transition-colors text-sm whitespace-nowrap flex items-center gap-1">
                                <i class="fa fa-plus"></i> {{ __('lang.add') }}
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $operatingLocation->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#0C3183] shadow-sm focus:border-[#0C3183] focus:ring focus:ring-[#0C3183] focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">{{ __('lang.active') }}</span>
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.operating-locations.index') }}"
                       class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition-colors">
                        {{ __('lang.cancel') }}
                    </a>
                    <button type="submit"
                            class="bg-[#0C3183] text-white px-4 py-2 rounded-md hover:bg-blue-800 transition-colors">
                        {{ __('lang.update_operating_location') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
