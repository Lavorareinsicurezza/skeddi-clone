@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-6">
        <!-- Page Header - Compact -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $company->name }}</h1>
                    <p class="text-gray-500 text-sm flex items-center gap-2 mt-1">
                        <i class="fa fa-building text-[#0C3183]"></i>
                        <span>{{ __('lang.company_information') }}</span>
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.companies.edit', $company->id) }}"
                        class="bg-[#0C3183] text-white px-4 py-2 rounded-lg hover:bg-blue-800 shadow-sm transition-all flex items-center gap-2 text-sm">
                        <i class="fa fa-edit"></i>
                        <span>{{ __('lang.edit') }}</span>
                    </a>
                    <a href="{{ route('admin.companies.index') }}"
                        class="bg-white text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 shadow-sm border border-gray-200 transition-all flex items-center gap-2 text-sm">
                        <i class="fa fa-arrow-left"></i>
                        <span>{{ __('lang.back') }}</span>
                    </a>
                </div>
            </div>

            <!-- Status Badges - Compact -->
            <div class="flex gap-2">
                @if($company->freeze_company)
                    <span class="bg-orange-100 text-orange-800 text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                        <i class="fa fa-lock text-xs"></i>
                        {{ __('lang.company_frozen') }}
                    </span>
                @else
                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                        <i class="fa fa-unlock text-xs"></i>
                        {{ __('lang.active') }}
                    </span>
                @endif

                @if($company->send_deadline_notification)
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                        <i class="fa fa-bell text-xs"></i>
                        {{ __('lang.notifications_enabled') }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Main Grid Layout - Compact -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-4">

                <!-- Office Locations - MOVED TO TOP -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-4 py-3">
                        <h2 class="text-base font-semibold  flex items-center gap-2">
                            <i class="fa fa-map-marker-alt"></i>
                            {{ __('lang.office_locations') }}
                        </h2>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            <div class="flex items-start gap-2">
                                <div class="bg-purple-50 p-2 rounded">
                                    <i class="fa fa-building text-purple-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 uppercase font-medium">{{ __('lang.registered_office') }}</p>
                                    <p class="text-gray-900 text-sm">{{ $company->registered_office ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2">
                                <div class="bg-purple-50 p-2 rounded">
                                    <i class="fa fa-briefcase text-purple-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 uppercase font-medium">{{ __('lang.operating_office') }}</p>
                                    <p class="text-gray-900 text-sm">{{ $company->operating_office ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Information - Compact -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-[#0C3183] to-blue-700 px-4 py-3">
                        <h2 class="text-base font-semibold flex items-center gap-2">
                            <i class="fa fa-info-circle"></i>
                            {{ __('lang.basic_information') }}
                        </h2>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-start gap-2">
                                <div class="bg-blue-50 p-2 rounded">
                                    <i class="fa fa-file-text text-[#0C3183] text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">{{ __('lang.vat_number') }}</p>
                                    <p class="text-gray-900 font-semibold text-sm">{{ $company->vat_number ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2">
                                <div class="bg-blue-50 p-2 rounded">
                                    <i class="fa fa-hashtag text-[#0C3183] text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">{{ __('lang.tax_code') }}</p>
                                    <p class="text-gray-900 font-semibold text-sm">{{ $company->tax_code ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2">
                                <div class="bg-blue-50 p-2 rounded">
                                    <i class="fa fa-chart-bar text-[#0C3183] text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">{{ __('lang.ateco') }}</p>
                                    <p class="text-gray-900 font-semibold text-sm">{{ $company->ateco ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2">
                                <div class="bg-blue-50 p-2 rounded">
                                    <i class="fa fa-barcode text-[#0C3183] text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">{{ __('lang.sdi') }}</p>
                                    <p class="text-gray-900 font-semibold text-sm">{{ $company->sdi ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information - Compact -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 py-3">
                        <h2 class="text-base font-semibold  flex items-center gap-2">
                            <i class="fa fa-address-book"></i>
                            {{ __('lang.contact_information') }}
                        </h2>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-start gap-2">
                                <div class="bg-green-50 p-2 rounded">
                                    <i class="fa fa-envelope text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">{{ __('lang.main_email') }}</p>
                                    <p class="text-gray-900 text-sm break-all">{{ $company->main_email ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2">
                                <div class="bg-green-50 p-2 rounded">
                                    <i class="fa fa-certificate text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">{{ __('lang.pec_email') }}</p>
                                    <p class="text-gray-900 text-sm break-all">{{ $company->pec_email ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2">
                                <div class="bg-green-50 p-2 rounded">
                                    <i class="fa fa-phone text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">{{ __('lang.phone') }}</p>
                                    <p class="text-gray-900 text-sm">{{ $company->phone ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2">
                                <div class="bg-green-50 p-2 rounded">
                                    <i class="fa fa-phone text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">{{ __('lang.phone_2') }}</p>
                                    <p class="text-gray-900 text-sm">{{ $company->phone_2 ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2 md:col-span-2">
                                <div class="bg-green-50 p-2 rounded">
                                    <i class="fa fa-user text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">{{ __('lang.company_contact_person') }}</p>
                                    <p class="text-gray-900 text-sm">{{ $company->company_contact_person ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Safety Personnel - Compact with Dropdowns -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-4 py-3">
                        <h2 class="text-base font-semibold  flex items-center gap-2">
                            <i class="fa fa-shield-alt"></i>
                            {{ __('lang.safety_and_personnel') }}
                        </h2>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-start gap-2">
                                <div class="bg-orange-50 p-2 rounded">
                                    <i class="fa fa-user-tie text-orange-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">{{ __('lang.employer') }}</p>
                                    <p class="text-gray-900 text-sm">{{ $company->employer ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2">
                                <div class="bg-orange-50 p-2 rounded">
                                    <i class="fa fa-user-shield text-orange-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">{{ __('lang.head_of_prevention') }}</p>
                                    <p class="text-gray-900 text-sm">{{ $company->head_of_prevention ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2">
                                <div class="bg-orange-50 p-2 rounded">
                                    <i class="fa fa-users text-orange-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">{{ __('lang.safety_representative') }}</p>
                                    <p class="text-gray-900 text-sm">{{ $company->workers_safety_representative ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-2">
                                <div class="bg-orange-50 p-2 rounded">
                                    <i class="fa fa-user-md text-orange-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium">{{ __('lang.company_doctor') }}</p>
                                    <p class="text-gray-900 text-sm">{{ $company->company_doctor ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Safety Risk Indicators with Dropdowns -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <!-- Workplace Safety Risk Dropdown -->
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-2">{{ __('lang.workplace_safety_risk') }}</p>
                                    <div class="relative">
                                        <select class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" disabled>
                                            <option value="low" {{ $company->workplace_safety_risk == 'low' ? 'selected' : '' }}>{{ __('lang.low_risk') }}</option>
                                            <option value="medium" {{ $company->workplace_safety_risk == 'medium' ? 'selected' : '' }}>{{ __('lang.medium_risk') }}</option>
                                            <option value="high" {{ $company->workplace_safety_risk == 'high' ? 'selected' : '' }}>{{ __('lang.high_risk') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Subject to CPI -->
                                <div class="bg-gray-50 rounded-lg p-3 text-center flex flex-col justify-center">
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-2">{{ __('lang.subject_to_cpi') }}</p>
                                    @if($company->subject_to_cpi)
                                        <span class="inline-flex items-center justify-center gap-2 text-green-600 font-semibold text-sm">
                                            <i class="fa fa-check-circle"></i>
                                            <span>{{ __('lang.yes') }}</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center gap-2 text-red-600 font-semibold text-sm">
                                            <i class="fa fa-times-circle"></i>
                                            <span>{{ __('lang.no') }}</span>
                                        </span>
                                    @endif
                                </div>

                                <!-- Fire Risk Dropdown -->
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-2">{{ __('lang.rischio_antincendio') }}</p>
                                    <div class="relative">
                                        <select class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" disabled>
                                            <option value="level_1" {{ $company->rischio_antincendio == 'level_1' ? 'selected' : '' }}>{{ __('lang.level_1') }}</option>
                                            <option value="level_2" {{ $company->rischio_antincendio == 'level_2' ? 'selected' : '' }}>{{ __('lang.level_2') }}</option>
                                            <option value="level_3" {{ $company->rischio_antincendio == 'level_3' ? 'selected' : '' }}>{{ __('lang.level_3') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Card - Compact -->
                @if($company->notes)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-[#0C3183] to-blue-700 px-4 py-3">
                        <h2 class="text-base font-semibold flex items-center gap-2">
                            <i class="fa fa-sticky-note"></i>
                            {{ __('lang.notes') }}
                        </h2>
                    </div>
                    <div class="p-4">
                        <div class="bg-amber-50 border-l-4 border-amber-500 p-3 rounded">
                            <p class="text-gray-700 text-sm whitespace-pre-wrap">{{ $company->notes }}</p>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- Right Column - Compact -->
            <div class="space-y-4">

                <!-- External Professional Contacts - Compact -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-4 py-3">
                        <h2 class="text-base font-semibold  flex items-center gap-2">
                            <i class="fa fa-user-friends"></i>
                            {{ __('lang.professional_contacts') }}
                        </h2>
                    </div>
                    <div class="p-4">
                        <!-- Accountant -->
                        <div class="mb-4">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fa fa-calculator text-teal-600 text-sm"></i>
                                <h3 class="text-xs font-semibold text-gray-700 uppercase">{{ __('lang.accountant') }}</h3>
                            </div>
                            <div class="space-y-2 ml-5">
                                <div class="flex items-start gap-2">
                                    <i class="fa fa-user text-xs text-gray-400 mt-0.5"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">{{ __('lang.name') }}</p>
                                        <p class="text-gray-900 text-sm">{{ $company->accountant_name ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-2">
                                    <i class="fa fa-phone text-xs text-gray-400 mt-0.5"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">{{ __('lang.phone') }}</p>
                                        <p class="text-gray-900 text-sm">{{ $company->accountant_phone ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-2">
                                    <i class="fa fa-envelope text-xs text-gray-400 mt-0.5"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">{{ __('lang.email') }}</p>
                                        <p class="text-gray-900 text-sm break-all">{{ $company->accountant_email ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Labor Consultant -->
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fa fa-briefcase text-teal-600 text-sm"></i>
                                <h3 class="text-xs font-semibold text-gray-700 uppercase">{{ __('lang.labor_consultant') }}</h3>
                            </div>
                            <div class="space-y-2 ml-5">
                                <div class="flex items-start gap-2">
                                    <i class="fa fa-user text-xs text-gray-400 mt-0.5"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">{{ __('lang.name') }}</p>
                                        <p class="text-gray-900 text-sm">{{ $company->labor_consultant_name ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-2">
                                    <i class="fa fa-phone text-xs text-gray-400 mt-0.5"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">{{ __('lang.phone') }}</p>
                                        <p class="text-gray-900 text-sm">{{ $company->labor_consultant_phone ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-2">
                                    <i class="fa fa-envelope text-xs text-gray-400 mt-0.5"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">{{ __('lang.email') }}</p>
                                        <p class="text-gray-900 text-sm break-all">{{ $company->labor_consultant_email ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selected Contacts - Compact -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 py-3">
                        <h2 class="text-base font-semibold  flex items-center gap-2">
                            <i class="fa fa-at"></i>
                            {{ __('lang.selected_contacts') }}
                        </h2>
                    </div>
                    <div class="p-4">
                        @if($company->contacts && is_array($company->contacts))
                            <div class="space-y-2">
                                @foreach($company->contacts as $contact)
                                    <div class="flex items-center gap-2 bg-indigo-50 text-indigo-700 px-3 py-2 rounded-lg">
                                        <i class="fa fa-envelope text-xs"></i>
                                        <span class="text-xs font-medium break-all">{{ $contact }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <i class="fa fa-inbox text-3xl text-gray-300 mb-2"></i>
                                <p class="text-gray-500 text-xs">{{ __('lang.no_contacts_selected') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
