@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.edit_company') }}</h1>
        </div>

        <form action="{{ route('admin.companies.update', $company->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

            <!-- Row 1 -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.company_name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="company_name" value="{{ old('company_name', $company->name) }}" placeholder="{{ __('lang.enter_company_name') }}"
                        class="w-full border @error('company_name') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('company_name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.vat_number') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="vat_number" value="{{ old('vat_number', $company->vat_number) }}" placeholder="{{ __('lang.enter_vat_number') }}"
                        class="w-full border @error('vat_number') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('vat_number')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.tax_code') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="tax_code" value="{{ old('tax_code', $company->tax_code) }}" placeholder="{{ __('lang.enter_tax_code') }}"
                        class="w-full border @error('tax_code') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('tax_code')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.ateco') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="ateco" value="{{ old('ateco', $company->ateco) }}" placeholder="{{ __('lang.enter_ateco_code') }}"
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
                    <input type="text" name="sdi" value="{{ old('sdi', $company->sdi) }}" placeholder="{{ __('lang.enter_sdi_code') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.registered_office') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="registered_office" value="{{ old('registered_office', $company->registered_office) }}" placeholder="{{ __('lang.enter_registered_office_address') }}"
                        class="w-full border @error('registered_office') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('registered_office')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.operating_office') }}</label>
                    <input type="text" name="operating_office" value="{{ old('operating_office', $company->operating_office) }}" placeholder="{{ __('lang.enter_operating_office_address') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.main_email') }} <span class="text-red-500">*</span></label>
                    <input type="email" name="main_email" value="{{ old('main_email', $company->main_email) }}" placeholder="company@example.com"
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
                    <input type="email" name="pec_email" value="{{ old('pec_email', $company->pec_email) }}" placeholder="pec@example.com"
                        class="w-full border @error('pec_email') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('pec_email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.phone') }} <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone" value="{{ old('phone', $company->phone) }}" placeholder="+39 123 456 7890"
                        class="w-full border @error('phone') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('phone')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.phone_2') }}</label>
                    <input type="tel" name="phone_2" value="{{ old('phone_2', $company->phone_2) }}" placeholder="+39 123 456 7890"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.company_contact_person') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="company_contact_person" value="{{ old('company_contact_person', $company->company_contact_person) }}" placeholder="{{ __('lang.enter_contact_person_name') }}"
                        class="w-full border @error('company_contact_person') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('company_contact_person')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 4 -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.employer') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="employer" value="{{ old('employer', $company->employer) }}" placeholder="{{ __('lang.enter_employer_name') }}"
                        class="w-full border @error('employer') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('employer')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.head_of_prevention_service') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="head_of_prevention" value="{{ old('head_of_prevention', $company->head_of_prevention) }}" placeholder="{{ __('lang.enter_head_of_prevention_name') }}"
                        class="w-full border @error('head_of_prevention') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('head_of_prevention')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.workers_safety_representative') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="workers_safety_representative" value="{{ old('workers_safety_representative', $company->workers_safety_representative) }}" placeholder="{{ __('lang.enter_safety_representative_name') }}"
                        class="w-full border @error('workers_safety_representative') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('workers_safety_representative')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.company_doctor') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="company_doctor" value="{{ old('company_doctor', $company->company_doctor) }}" placeholder="{{ __('lang.enter_company_doctor_name') }}"
                        class="w-full border @error('company_doctor') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('company_doctor')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 5 - Toggles/Radio/Dropdown -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50">
                        <span class="text-sm font-medium text-gray-800">{{ __('lang.workplace_safety_risk') }}</span>
                        <input type="checkbox" name="workplace_safety_risk" value="1" {{ old('workplace_safety_risk', $company->workplace_safety_risk) ? 'checked' : '' }} class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:ring-offset-2">
                    </label>
                </div>
                <div>
                    <label class="border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50">
                        <span class="text-sm font-medium text-gray-800">{{ __('lang.subject_to_cpi') }}:</span>
                        <input type="checkbox" name="subject_to_cpi" value="1" {{ old('subject_to_cpi', $company->subject_to_cpi) ? 'checked' : '' }} class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:ring-offset-2">
                    </label>
                </div>
                <div>
                    <select name="rischio_antincendio"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent appearance-none bg-white cursor-pointer">
                        <option value="Rischio Antincendio" {{ old('rischio_antincendio', $company->rischio_antincendio) == 'Rischio Antincendio' ? 'selected' : '' }}>{{ __('lang.rischio_antincendio') }}</option>
                        <option value="Option 2" {{ old('rischio_antincendio', $company->rischio_antincendio) == 'Option 2' ? 'selected' : '' }}>{{ __('lang.option_2') }}</option>
                        <option value="Option 3" {{ old('rischio_antincendio', $company->rischio_antincendio) == 'Option 3' ? 'selected' : '' }}>{{ __('lang.option_3') }}</option>
                    </select>
                </div>
            </div>

            <!-- External Professional Contacts -->
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">{{ __('lang.external_professional_contacts') }}*</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <input type="text" name="accountant_name" value="{{ old('accountant_name', $company->accountant_name) }}" placeholder="{{ __('lang.accountant_name') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    </div>
                    <div>
                        <input type="tel" name="accountant_phone" value="{{ old('accountant_phone', $company->accountant_phone) }}" placeholder="{{ __('lang.phone') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    </div>
                    <div>
                        <input type="email" name="accountant_email" value="{{ old('accountant_email', $company->accountant_email) }}" placeholder="{{ __('lang.e-mail') }}"
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
                        <input type="text" name="labor_consultant_name" value="{{ old('labor_consultant_name', $company->labor_consultant_name) }}" placeholder="{{ __('lang.labot_consultant_name') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    </div>
                    <div>
                        <input type="tel" name="labor_consultant_phone" value="{{ old('labor_consultant_phone', $company->labor_consultant_phone) }}" placeholder="{{ __('lang.phone') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    </div>
                    <div>
                        <input type="email" name="labor_consultant_email" value="{{ old('labor_consultant_email', $company->labor_consultant_email) }}" placeholder="{{ __('lang.e-mail') }}"
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
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent resize-none">{{ old('notes', $company->notes) }}</textarea>
            </div>

            <!-- Notification and Freeze Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50">
                        <span class="text-sm font-medium text-gray-800">{{ __('lang.send_deadline_notification') }}</span>
                        <input type="checkbox" name="send_deadline_notification" value="1" {{ old('send_deadline_notification', $company->send_deadline_notification) ? 'checked' : '' }} class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:ring-offset-2">
                    </label>
                </div>
                <div>
                    <label class="border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50">
                        <span class="text-sm font-medium text-gray-800">{{ __('lang.freeze_company') }}</span>
                        <input type="checkbox" name="freeze_company" value="1" {{ old('freeze_company', $company->freeze_company) ? 'checked' : '' }} class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:ring-offset-2">
                    </label>
                </div>
            </div>

            <!-- Contacts Selection -->
            <div>
                <h3 class="text-sm font-semibold text-gray-800 mb-4">{{ __('lang.contacts_select_at_least_one') }} <span class="text-red-500">*</span></h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @foreach($userEmails as $email)
                    <div>
                        <label class="border @error('contacts') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50">
                            <span class="text-sm text-gray-800">{{ $email}}</span>
                            <input type="checkbox" name="contacts[]" value="{{ $email}}" {{ in_array($email, old('contacts') ?: (is_array($company->contacts) ? $company->contacts : [])) ? 'checked' : '' }} class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:ring-offset-2">
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('contacts')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-6">
                <div class="text-end">
                    <a href="{{ route('admin.companies.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg mt-6 mr-2 inline-block">{{ __('lang.cancel') }}</a>
                    <button type="submit" class="bg-[#0C3183] text-white px-6 py-3 rounded-lg mt-6">{{ __('lang.update') }}</button>
                </div>
            </div>

            </div>
        </form>
    </div>
@endsection
