@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Edit Company</h1>
        </div>

        <form action="{{ route('admin.companies.update', $company->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

            <!-- Row 1 -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Company Name <span class="text-red-500">*</span></label>
                    <input type="text" name="company_name" value="{{ old('company_name', $company->name) }}" placeholder="Enter company name"
                        class="w-full border @error('company_name') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('company_name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">VAT Number <span class="text-red-500">*</span></label>
                    <input type="text" name="vat_number" value="{{ old('vat_number', $company->vat_number) }}" placeholder="Enter VAT number"
                        class="w-full border @error('vat_number') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('vat_number')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tax Code <span class="text-red-500">*</span></label>
                    <input type="text" name="tax_code" value="{{ old('tax_code', $company->tax_code) }}" placeholder="Enter tax code"
                        class="w-full border @error('tax_code') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('tax_code')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ateco <span class="text-red-500">*</span></label>
                    <input type="text" name="ateco" value="{{ old('ateco', $company->ateco) }}" placeholder="Enter ATECO code"
                        class="w-full border @error('ateco') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('ateco')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 2 -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SDI</label>
                    <input type="text" name="sdi" value="{{ old('sdi', $company->sdi) }}" placeholder="Enter SDI code"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Registered office <span class="text-red-500">*</span></label>
                    <input type="text" name="registered_office" value="{{ old('registered_office', $company->registered_office) }}" placeholder="Enter registered office address"
                        class="w-full border @error('registered_office') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('registered_office')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Operating office</label>
                    <input type="text" name="operating_office" value="{{ old('operating_office', $company->operating_office) }}" placeholder="Enter operating office address"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Main email <span class="text-red-500">*</span></label>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">PEC (certified email)</label>
                    <input type="email" name="pec_email" value="{{ old('pec_email', $company->pec_email) }}" placeholder="pec@example.com"
                        class="w-full border @error('pec_email') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('pec_email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone" value="{{ old('phone', $company->phone) }}" placeholder="+39 123 456 7890"
                        class="w-full border @error('phone') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('phone')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone 2</label>
                    <input type="tel" name="phone_2" value="{{ old('phone_2', $company->phone_2) }}" placeholder="+39 123 456 7890"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Company contact person <span class="text-red-500">*</span></label>
                    <input type="text" name="company_contact_person" value="{{ old('company_contact_person', $company->company_contact_person) }}" placeholder="Enter contact person name"
                        class="w-full border @error('company_contact_person') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('company_contact_person')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 4 -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employer <span class="text-red-500">*</span></label>
                    <input type="text" name="employer" value="{{ old('employer', $company->employer) }}" placeholder="Enter employer name"
                        class="w-full border @error('employer') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('employer')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Head of the Prevention and Protection
                        Service <span class="text-red-500">*</span></label>
                    <input type="text" name="head_of_prevention" value="{{ old('head_of_prevention', $company->head_of_prevention) }}" placeholder="Enter head of prevention name"
                        class="w-full border @error('head_of_prevention') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('head_of_prevention')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Workers' Safety Representative <span class="text-red-500">*</span></label>
                    <input type="text" name="workers_safety_representative" value="{{ old('workers_safety_representative', $company->workers_safety_representative) }}" placeholder="Enter safety representative name"
                        class="w-full border @error('workers_safety_representative') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    @error('workers_safety_representative')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Company doctor <span class="text-red-500">*</span></label>
                    <input type="text" name="company_doctor" value="{{ old('company_doctor', $company->company_doctor) }}" placeholder="Enter company doctor name"
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
                        <span class="text-sm font-medium text-gray-800">Workplace Safety Risk</span>
                        <input type="checkbox" name="workplace_safety_risk" value="1" {{ old('workplace_safety_risk', $company->workplace_safety_risk) ? 'checked' : '' }} class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:ring-offset-2">
                    </label>
                </div>
                <div>
                    <label class="border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50">
                        <span class="text-sm font-medium text-gray-800">Subject to CPI:</span>
                        <input type="checkbox" name="subject_to_cpi" value="1" {{ old('subject_to_cpi', $company->subject_to_cpi) ? 'checked' : '' }} class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:ring-offset-2">
                    </label>
                </div>
                <div>
                    <select name="rischio_antincendio"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent appearance-none bg-white cursor-pointer">
                        <option value="Rischio Antincendio" {{ old('rischio_antincendio', $company->rischio_antincendio) == 'Rischio Antincendio' ? 'selected' : '' }}>Rischio Antincendio</option>
                        <option value="Option 2" {{ old('rischio_antincendio', $company->rischio_antincendio) == 'Option 2' ? 'selected' : '' }}>Option 2</option>
                        <option value="Option 3" {{ old('rischio_antincendio', $company->rischio_antincendio) == 'Option 3' ? 'selected' : '' }}>Option 3</option>
                    </select>
                </div>
            </div>

            <!-- External Professional Contacts -->
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">External Professional Contacts*</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <input type="text" name="accountant_name" value="{{ old('accountant_name', $company->accountant_name) }}" placeholder="Accountant: name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    </div>
                    <div>
                        <input type="tel" name="accountant_phone" value="{{ old('accountant_phone', $company->accountant_phone) }}" placeholder="Phone"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    </div>
                    <div>
                        <input type="email" name="accountant_email" value="{{ old('accountant_email', $company->accountant_email) }}" placeholder="Email"
                            class="w-full border @error('accountant_email') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        @error('accountant_email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Labor Consultant -->
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">Labor Consultant</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <input type="text" name="labor_consultant_name" value="{{ old('labor_consultant_name', $company->labor_consultant_name) }}" placeholder="Name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    </div>
                    <div>
                        <input type="tel" name="labor_consultant_phone" value="{{ old('labor_consultant_phone', $company->labor_consultant_phone) }}" placeholder="Phone"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    </div>
                    <div>
                        <input type="email" name="labor_consultant_email" value="{{ old('labor_consultant_email', $company->labor_consultant_email) }}" placeholder="Email"
                            class="w-full border @error('labor_consultant_email') border-red-500 @else border-gray-300 @enderror rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                        @error('labor_consultant_email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">Notes</h3>
                <textarea name="notes" rows="4" placeholder="Enter any additional notes or comments"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent resize-none">{{ old('notes', $company->notes) }}</textarea>
            </div>

            <!-- Notification and Freeze Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50">
                        <span class="text-sm font-medium text-gray-800">Send Deadline Notification</span>
                        <input type="checkbox" name="send_deadline_notification" value="1" {{ old('send_deadline_notification', $company->send_deadline_notification) ? 'checked' : '' }} class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:ring-offset-2">
                    </label>
                </div>
                <div>
                    <label class="border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50">
                        <span class="text-sm font-medium text-gray-800">Freeze Company</span>
                        <input type="checkbox" name="freeze_company" value="1" {{ old('freeze_company', $company->freeze_company) ? 'checked' : '' }} class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:ring-offset-2">
                    </label>
                </div>
            </div>

            <!-- Contacts Selection -->
            <div>
                <h3 class="text-sm font-semibold text-gray-800 mb-4">Contacts Select at least one <span class="text-red-500">*</span></h3>
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
                    <a href="{{ route('admin.companies.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg mt-6 mr-2 inline-block">Cancel</a>
                    <button type="submit" class="bg-[#0C3183] text-white px-6 py-3 rounded-lg mt-6">Update</button>
                </div>
            </div>

            </div>
        </form>
    </div>
@endsection
