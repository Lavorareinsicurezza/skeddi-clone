@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-6">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.create_operating_location') }}</h1>
                    <p class="text-gray-500 text-sm flex items-center gap-2 mt-1">
                        <i class="fa fa-map-marker-alt text-[#0C3183]"></i>
                        <span>{{ __('lang.add_new_operating_location') }}</span>
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

        <!-- Create Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <form action="{{ route('admin.operating-locations.store') }}" method="POST" class="p-6">
                @csrf

                <!-- Location Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('lang.location_name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                           value="{{ old('name') }}"
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
                                   value="{{ old('address') }}"
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
                                   value="{{ old('site_contact_name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent"
                                   placeholder="{{ __('lang.contact_name_placeholder') }}">
                        </div>

                        <div>
                            <label for="site_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('lang.contact_phone') }}
                            </label>
                            <input type="text" name="site_contact_phone" id="site_contact_phone"
                                   value="{{ old('site_contact_phone') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent"
                                   placeholder="{{ __('lang.contact_phone_placeholder') }}">
                        </div>

                        <div>
                            <label for="site_contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('lang.contact_email') }}
                            </label>
                            <input type="email" name="site_contact_email" id="site_contact_email"
                                   value="{{ old('site_contact_email') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent"
                                   placeholder="{{ __('lang.contact_email_placeholder') }}">
                            @error('site_contact_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SMTP Configuration -->
                <div class="mb-6" x-data="smtpProfileSelector()">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('lang.smtp_configuration') }}</h3>

                    <!-- SMTP Profile Selector -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Seleziona Profilo SMTP
                            <span class="text-xs text-gray-500">(opzionale - auto-compila i campi sottostanti)</span>
                        </label>
                        <select name="smtp_profile_id" x-model="selectedProfileId" @change="loadProfile()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                            <option value="">-- Seleziona un profilo o inserisci manualmente --</option>
                            @foreach($smtpProfiles as $profile)
                                <option value="{{ $profile->id }}" {{ old('smtp_profile_id') == $profile->id ? 'selected' : '' }}>
                                    {{ $profile->name }}
                                </option>
                            @endforeach
                        </select>
                        @can('create smtp-profiles')
                        <div class="mt-2">
                            <a href="{{ route('admin.smtp-profiles.create') }}" target="_blank"
                                class="text-sm text-blue-600 hover:text-blue-800 inline-flex items-center">
                                <i class="fa fa-plus mr-1"></i> Crea nuovo profilo SMTP
                            </a>
                        </div>
                        @endcan
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="smtp_host" class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                            <input type="text" name="smtp_host" id="smtp_host" value="{{ old('smtp_host') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        </div>
                        <div>
                            <label for="smtp_port" class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                            <input type="text" name="smtp_port" id="smtp_port" value="{{ old('smtp_port') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        </div>
                        <div>
                            <label for="smtp_username" class="block text-sm font-medium text-gray-700 mb-2">SMTP Username</label>
                            <input type="text" name="smtp_username" id="smtp_username" value="{{ old('smtp_username') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        </div>
                        <div>
                            <label for="smtp_password" class="block text-sm font-medium text-gray-700 mb-2">SMTP Password</label>
                            <input type="password" name="smtp_password" id="smtp_password" value="{{ old('smtp_password') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        </div>
                        <div>
                            <label for="smtp_encryption" class="block text-sm font-medium text-gray-700 mb-2">SMTP Encryption</label>
                            <select name="smtp_encryption" id="smtp_encryption" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                                <option value="">None</option>
                                <option value="tls" {{ old('smtp_encryption') == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ old('smtp_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                        </div>
                        <div>
                            <label for="smtp_from_address" class="block text-sm font-medium text-gray-700 mb-2">From Address</label>
                            <input type="email" name="smtp_from_address" id="smtp_from_address" value="{{ old('smtp_from_address') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        </div>
                        <div>
                            <label for="smtp_from_name" class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                            <input type="text" name="smtp_from_name" id="smtp_from_name" value="{{ old('smtp_from_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
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
                        {{ __('lang.create_operating_location') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // SMTP Profile Selector
        function smtpProfileSelector() {
            return {
                selectedProfileId: '{{ old('smtp_profile_id') }}',
                profiles: @json($smtpProfiles),

                loadProfile() {
                    if (!this.selectedProfileId) {
                        return;
                    }

                    const profile = this.profiles.find(p => p.id == this.selectedProfileId);
                    if (profile) {
                        // Auto-fill SMTP fields
                        document.getElementById('smtp_host').value = profile.host || '';
                        document.getElementById('smtp_port').value = profile.port || '';
                        document.getElementById('smtp_username').value = profile.username || '';
                        document.getElementById('smtp_password').value = profile.password || '';
                        document.getElementById('smtp_from_address').value = profile.from_address || '';
                        document.getElementById('smtp_from_name').value = profile.from_name || '';

                        // Set encryption dropdown
                        const encryptionSelect = document.getElementById('smtp_encryption');
                        if (profile.encryption) {
                            encryptionSelect.value = profile.encryption;
                        } else {
                            encryptionSelect.value = '';
                        }
                    }
                }
            }
        }
    </script>
@endsection
