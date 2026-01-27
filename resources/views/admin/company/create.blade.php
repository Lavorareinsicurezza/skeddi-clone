@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.company_information') }}</h1>
        </div>

        <form action="{{ route('admin.companies.store') }}" method="POST">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

                <!-- Row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.company_name') }} <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}"
                            placeholder="{{ __('lang.enter_company_name') }}"
                            class="w-full border @error('company_name') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        @error('company_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.vat_number') }} <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="vat_number" value="{{ old('vat_number') }}"
                            placeholder="{{ __('lang.enter_vat_number') }}"
                            class="w-full border @error('vat_number') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        @error('vat_number')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.tax_code') }} </label>
                        <input type="text" name="tax_code" value="{{ old('tax_code') }}"
                            placeholder="{{ __('lang.enter_tax_code') }}"
                            class="w-full border @error('tax_code') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        @error('tax_code')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.ateco') }} </label>
                        <input type="text" name="ateco" value="{{ old('ateco') }}"
                            placeholder="{{ __('lang.enter_ateco_code') }}"
                            class="w-full border @error('ateco') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        @error('ateco')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.sdi') }}</label>
                        <input type="text" name="sdi" value="{{ old('sdi') }}"
                            placeholder="{{ __('lang.enter_sdi_code') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.registered_office') }}
                            </label>
                        <input type="text" name="registered_office" value="{{ old('registered_office') }}"
                            placeholder="{{ __('lang.enter_registered_office_address') }}"
                            class="w-full border @error('registered_office') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        @error('registered_office')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.operating_office') }}</label>
                        <input type="text" name="operating_office" value="{{ old('operating_office') }}"
                            placeholder="{{ __('lang.enter_operating_office_address') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    </div> --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.main_email') }} </label>
                        <input type="email" name="main_email" value="{{ old('main_email') }}"
                            placeholder="company@example.com"
                            class="w-full border @error('main_email') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        @error('main_email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.pec_email') }}</label>
                        <input type="email" name="pec_email" value="{{ old('pec_email') }}" placeholder="pec@example.com"
                            class="w-full border @error('pec_email') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        @error('pec_email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.phone') }} </label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+39 123 456 7890"
                            class="w-full border @error('phone') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.phone_2') }}</label>
                        <input type="tel" name="phone_2" value="{{ old('phone_2') }}" placeholder="+39 123 456 7890"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.company_contact_person') }}
                            </label>
                        <input type="text" name="company_contact_person" value="{{ old('company_contact_person') }}"
                            placeholder="{{ __('lang.enter_contact_person_name') }}"
                            class="w-full border @error('company_contact_person') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        @error('company_contact_person')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 4 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.employer') }} </label>
                        <input type="text" name="employer" value="{{ old('employer') }}"
                            placeholder="{{ __('lang.enter_employer_name') }}"
                            class="w-full border @error('employer') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        @error('employer')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.head_of_prevention_service') }}
                            </label>
                        <input type="text" name="head_of_prevention" value="{{ old('head_of_prevention') }}"
                            placeholder="{{ __('lang.enter_head_of_prevention_name') }}"
                            class="w-full border @error('head_of_prevention') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        @error('head_of_prevention')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.workers_safety_representative') }}
                            </label>
                        <input type="text" name="workers_safety_representative"
                            value="{{ old('workers_safety_representative') }}"
                            placeholder="{{ __('lang.enter_safety_representative_name') }}"
                            class="w-full border @error('workers_safety_representative') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        @error('workers_safety_representative')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.company_doctor') }} </label>
                        <input type="text" name="company_doctor" value="{{ old('company_doctor') }}"
                            placeholder="{{ __('lang.enter_company_doctor_name') }}"
                            class="w-full border @error('company_doctor') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        @error('company_doctor')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 5 - Toggles/Radio/Dropdown -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <select name="workplace_safety_risk"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent appearance-none bg-white cursor-pointer">
                            <option value="">{{ __('lang.workplace_safety_risk') }}</option>
                            <option value="low">{{ __('lang.low_risk') }}</option>
                            <option value="medium">{{ __('lang.medium_risk') }}</option>
                            <option value="high">{{ __('lang.high_risk') }}</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50">
                            <span class="text-sm font-medium text-gray-800">{{ __('lang.subject_to_cpi') }}:</span>
                            <input type="checkbox" name="subject_to_cpi" value="1"
                                class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:ring-offset-2">
                        </label>
                    </div>
                    <div>
                        <select name="rischio_antincendio"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent appearance-none bg-white cursor-pointer">
                            <option value="">{{ __('lang.rischio_antincendio') }}</option>
                            <option value="level_1">{{ __('lang.level_1') }}</option>
                            <option value="level_2">{{ __('lang.level_2') }}</option>
                            <option value="level_3">{{ __('lang.level_3') }}</option>
                        </select>
                    </div>
                </div>

                <!-- External Professional Contacts -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">{{ __('lang.external_professional_contacts') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <input type="text" name="accountant_name" value="{{ old('accountant_name') }}"
                                placeholder="{{ __('lang.accountant_name') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        </div>
                        <div>
                            <input type="tel" name="accountant_phone" value="{{ old('accountant_phone') }}"
                                placeholder="{{ __('lang.phone') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        </div>
                        <div>
                            <input type="email" name="accountant_email" value="{{ old('accountant_email') }}"
                                placeholder="{{ __('lang.e-mail') }}"
                                class="w-full border @error('accountant_email') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                            @error('accountant_email')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Labor Consultant -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">{{ __('lang.labor_consultant') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <input type="text" name="labor_consultant_name"
                                value="{{ old('labor_consultant_name') }}"
                                placeholder="{{ __('lang.labot_consultant_name') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        </div>
                        <div>
                            <input type="tel" name="labor_consultant_phone"
                                value="{{ old('labor_consultant_phone') }}" placeholder="{{ __('lang.phone') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        </div>
                        <div>
                            <input type="email" name="labor_consultant_email"
                                value="{{ old('labor_consultant_email') }}" placeholder="{{ __('lang.e-mail') }}"
                                class="w-full border @error('labor_consultant_email') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                            @error('labor_consultant_email')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">{{ __('lang.notes') }}</h3>
                    <textarea name="notes" rows="4" placeholder="{{ __('lang.enter_additional_notes') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent resize-none">{{ old('notes') }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.agent') }}</label>
                    <input type="text" name="agent" value="{{ old('agent') }}" placeholder="{{ __('lang.enter_agent_name') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                </div>

                <!-- Notification and Freeze Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label
                            class="border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50">
                            <span
                                class="text-sm font-medium text-gray-800">{{ __('lang.send_deadline_notification') }}</span>
                            <input type="checkbox" name="send_deadline_notification" value="1"
                                class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:ring-offset-2">
                        </label>
                    </div>
                    <div>
                        <label
                            class="border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50">
                            <span class="text-sm font-medium text-gray-800">{{ __('lang.freeze_company') }}</span>
                            <input type="checkbox" name="freeze_company" value="1"
                                class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:ring-offset-2">
                        </label>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-800">{{ __('lang.operating_locations') }}</h3>
                        <button type="button" id="addLocationBtn" class="text-sm text-[#0C3183] font-medium">
                            {{ __('lang.add_operating_location') }}
                        </button>
                    </div>
                    <div id="locationsContainer" class="space-y-4"></div>
                    <template id="locationTemplate">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <input type="hidden" name="operating_locations[IDX][id]" value="">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.name') }}</label>
                                <input type="text" name="operating_locations[IDX][name]" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.address') }}</label>
                                <input type="text" name="operating_locations[IDX][address]" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.company_contact_person') }}</label>
                                <input type="text" name="operating_locations[IDX][site_contact_name]" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.phone') }}</label>
                                <input type="text" name="operating_locations[IDX][site_contact_phone]" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.email') }}</label>
                                <input type="email" name="operating_locations[IDX][site_contact_email]" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                            </div>
                            
                            <!-- SMTP Configuration -->
                            <div class="md:col-span-3 border-t border-gray-200 pt-4 mt-2">
                                <h4 class="text-sm font-semibold text-gray-800 mb-2">{{ __('lang.smtp_configuration') }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                                        <input type="text" name="operating_locations[IDX][smtp_host]" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                                        <input type="text" name="operating_locations[IDX][smtp_port]" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Username</label>
                                        <input type="text" name="operating_locations[IDX][smtp_username]" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Password</label>
                                        <input type="password" name="operating_locations[IDX][smtp_password]" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Encryption</label>
                                        <select name="operating_locations[IDX][smtp_encryption]" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                                            <option value="">None</option>
                                            <option value="tls">TLS</option>
                                            <option value="ssl">SSL</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">From Address</label>
                                        <input type="email" name="operating_locations[IDX][smtp_from_address]" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                                        <input type="text" name="operating_locations[IDX][smtp_from_name]" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-3 flex justify-end">
                                <button type="button" class="removeLocation text-sm text-red-600 font-medium">{{ __('lang.delete') }}</button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Contacts Selection -->
                {{-- <div>
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">{{ __('lang.contacts_select_at_least_one') }}
                        <span class="text-red-500">*</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @foreach ($userEmails as $email)
                            <div>
                                <label
                                    class="border @error('contacts') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50">
                                    <span class="text-sm text-gray-800">{{ $email->email }}</span>
                                    <input type="checkbox" name="contacts[]" value="{{ $email->email }}"
                                        {{ is_array(old('contacts')) && in_array($email->email, old('contacts')) ? 'checked' : '' }}
                                        class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:ring-offset-2">
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('contacts')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div> --}}

                <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-6">
                    <div class="text-end">
                        <button type="submit"
                            class="bg-[#0C3183] text-white px-6 py-3 rounded-lg mt-6">{{ __('lang.save') }}</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
@section('scripts')
<script>
let locIdx = 0;
const addBtn = document.getElementById('addLocationBtn');
const container = document.getElementById('locationsContainer');
const tpl = document.getElementById('locationTemplate').content;
function addLocation(initial = {}) {
    const node = document.importNode(tpl, true);
    node.querySelectorAll('input, select').forEach(inp => {
        inp.name = inp.name.replace('IDX', locIdx);
    });
    if (initial.name) node.querySelector('[name="operating_locations['+locIdx+'][name]"]').value = initial.name || '';
    if (initial.address) node.querySelector('[name="operating_locations['+locIdx+'][address]"]').value = initial.address || '';
    if (initial.site_contact_name) node.querySelector('[name="operating_locations['+locIdx+'][site_contact_name]"]').value = initial.site_contact_name || '';
    if (initial.site_contact_phone) node.querySelector('[name="operating_locations['+locIdx+'][site_contact_phone]"]').value = initial.site_contact_phone || '';
    if (initial.site_contact_email) node.querySelector('[name="operating_locations['+locIdx+'][site_contact_email]"]').value = initial.site_contact_email || '';
    container.appendChild(node);
    locIdx++;
    bindRemove();
}
function bindRemove() {
    container.querySelectorAll('.removeLocation').forEach(btn => {
        btn.onclick = function() {
            this.closest('.grid').remove();
        }
    });
}
addBtn.onclick = () => addLocation();
</script>
@endsection
@endsection
