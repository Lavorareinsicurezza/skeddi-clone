@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $company->name }}</h1>
                    <p class="text-gray-500 flex items-center gap-2">
                        <i class="fa fa-building text-[#0C3183]"></i>
                        <span>{{ __('lang.company_information') }}</span>
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.companies.edit', $company->id) }}"
                        class="bg-[#0C3183] text-white px-6 py-3 rounded-lg hover:bg-blue-800 shadow-sm transition-all flex items-center gap-2">
                        <i class="fa fa-edit"></i>
                        <span>{{ __('lang.edit') }}</span>
                    </a>
                    <a href="{{ route('admin.companies.index') }}"
                        class="bg-white text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 shadow-sm border border-gray-200 transition-all flex items-center gap-2">
                        <i class="fa fa-arrow-left"></i>
                        <span>{{ __('lang.back') }}</span>
                    </a>
                </div>
            </div>

            <!-- Status Badges -->
            <div class="flex gap-3 mt-4">
                @if($company->freeze_company)
                    <span class="bg-orange-100 text-orange-800 text-xs font-semibold px-4 py-2 rounded-full flex items-center gap-2">
                        <i class="fa fa-lock"></i>
                        {{ __('lang.company_frozen') }}
                    </span>
                @else
                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-4 py-2 rounded-full flex items-center gap-2">
                        <i class="fa fa-unlock"></i>
                        {{ __('lang.active') }}
                    </span>
                @endif

                @if($company->send_deadline_notification)
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-4 py-2 rounded-full flex items-center gap-2">
                        <i class="fa fa-bell"></i>
                        {{ __('lang.notifications_enabled') }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Column - Main Information -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Basic Information Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-[#0C3183] to-blue-700 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fa fa-info-circle"></i>
                            {{ __('lang.basic_information') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-start gap-3">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <i class="fa fa-file-text text-[#0C3183]"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">{{ __('lang.vat_number') }}</p>
                                    <p class="text-gray-900 font-semibold">{{ $company->vat_number ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <i class="fa fa-hashtag text-[#0C3183]"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">{{ __('lang.tax_code') }}</p>
                                    <p class="text-gray-900 font-semibold">{{ $company->tax_code ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <i class="fa fa-chart-bar text-[#0C3183]"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">{{ __('lang.ateco') }}</p>
                                    <p class="text-gray-900 font-semibold">{{ $company->ateco ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <i class="fa fa-barcode text-[#0C3183]"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">{{ __('lang.sdi') }}</p>
                                    <p class="text-gray-900 font-semibold">{{ $company->sdi ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-[#0C3183] to-blue-700 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fa fa-address-book"></i>
                            {{ __('lang.contact_information') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-start gap-3">
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <i class="fa fa-envelope text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">{{ __('lang.main_email') }}</p>
                                    <p class="text-gray-900 font-semibold break-all">{{ $company->main_email ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <i class="fa fa-certificate text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">{{ __('lang.pec_email') }}</p>
                                    <p class="text-gray-900 font-semibold break-all">{{ $company->pec_email ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <i class="fa fa-phone text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">{{ __('lang.phone') }}</p>
                                    <p class="text-gray-900 font-semibold">{{ $company->phone ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <i class="fa fa-phone text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">{{ __('lang.phone_2') }}</p>
                                    <p class="text-gray-900 font-semibold">{{ $company->phone_2 ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 md:col-span-2">
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <i class="fa fa-user text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">{{ __('lang.company_contact_person') }}</p>
                                    <p class="text-gray-900 font-semibold">{{ $company->company_contact_person ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-[#0C3183] to-blue-700 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fa fa-map-marker-alt"></i>
                            {{ __('lang.office_locations') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="bg-purple-50 p-3 rounded-lg">
                                    <i class="fa fa-building text-purple-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">{{ __('lang.registered_office') }}</p>
                                    <p class="text-gray-900">{{ $company->registered_office ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="bg-purple-50 p-3 rounded-lg">
                                    <i class="fa fa-briefcase text-purple-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">{{ __('lang.operating_office') }}</p>
                                    <p class="text-gray-900">{{ $company->operating_office ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Safety Personnel Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fa fa-shield-alt"></i>
                            {{ __('lang.safety_and_personnel') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-start gap-3">
                                <div class="bg-orange-50 p-3 rounded-lg">
                                    <i class="fa fa-user-tie text-orange-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">{{ __('lang.employer') }}</p>
                                    <p class="text-gray-900 font-semibold">{{ $company->employer ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="bg-orange-50 p-3 rounded-lg">
                                    <i class="fa fa-user-shield text-orange-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">{{ __('lang.head_of_prevention') }}</p>
                                    <p class="text-gray-900 font-semibold">{{ $company->head_of_prevention ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="bg-orange-50 p-3 rounded-lg">
                                    <i class="fa fa-users text-orange-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">{{ __('lang.safety_representative') }}</p>
                                    <p class="text-gray-900 font-semibold">{{ $company->workers_safety_representative ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="bg-orange-50 p-3 rounded-lg">
                                    <i class="fa fa-user-md text-orange-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">{{ __('lang.company_doctor') }}</p>
                                    <p class="text-gray-900 font-semibold">{{ $company->company_doctor ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Safety Risk Indicators -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-2">{{ __('lang.workplace_safety_risk') }}</p>
                                    @if($company->workplace_safety_risk)
                                        <span class="inline-flex items-center gap-2 text-green-600 font-semibold">
                                            <i class="fa fa-check-circle text-lg"></i>
                                            <span>{{ __('lang.yes') }}</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 text-red-600 font-semibold">
                                            <i class="fa fa-times-circle text-lg"></i>
                                            <span>{{ __('lang.no') }}</span>
                                        </span>
                                    @endif
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-2">{{ __('lang.subject_to_cpi') }}</p>
                                    @if($company->subject_to_cpi)
                                        <span class="inline-flex items-center gap-2 text-green-600 font-semibold">
                                            <i class="fa fa-check-circle text-lg"></i>
                                            <span>{{ __('lang.yes') }}</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 text-red-600 font-semibold">
                                            <i class="fa fa-times-circle text-lg"></i>
                                            <span>{{ __('lang.no') }}</span>
                                        </span>
                                    @endif
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-2">{{ __('lang.rischio_antincendio') }}</p>
                                    <p class="text-gray-900 font-semibold">{{ $company->rischio_antincendio ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Card -->
                @if($company->notes)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-[#0C3183] to-blue-700 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fa fa-sticky-note"></i>
                            {{ __('lang.notes') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $company->notes }}</p>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- Right Column - Additional Information -->
            <div class="space-y-6">

                <!-- External Professional Contacts Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fa fa-user-friends"></i>
                            {{ __('lang.professional_contacts') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <!-- Accountant -->
                        <div class="mb-6">
                            <div class="flex items-center gap-2 mb-3">
                                <i class="fa fa-calculator text-teal-600"></i>
                                <h3 class="text-sm font-semibold text-gray-700 uppercase">{{ __('lang.accountant') }}</h3>
                            </div>
                            <div class="space-y-2 ml-6">
                                <div class="flex items-start gap-2">
                                    <i class="fa fa-user text-xs text-gray-400 mt-1"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">{{ __('lang.name') }}</p>
                                        <p class="text-gray-900 font-medium">{{ $company->accountant_name ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-2">
                                    <i class="fa fa-phone text-xs text-gray-400 mt-1"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">Phone</p>
                                        <p class="text-gray-900 font-medium">{{ $company->accountant_phone ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-2">
                                    <i class="fa fa-envelope text-xs text-gray-400 mt-1"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">{{ __('lang.email') }}</p>
                                        <p class="text-gray-900 font-medium break-all">{{ $company->accountant_email ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Labor Consultant -->
                        <div class="pt-6 border-t border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <i class="fa fa-briefcase text-teal-600"></i>
                                <h3 class="text-sm font-semibold text-gray-700 uppercase">{{ __('lang.labor_consultant') }}</h3>
                            </div>
                            <div class="space-y-2 ml-6">
                                <div class="flex items-start gap-2">
                                    <i class="fa fa-user text-xs text-gray-400 mt-1"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">{{ __('lang.name') }}</p>
                                        <p class="text-gray-900 font-medium">{{ $company->labor_consultant_name ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-2">
                                    <i class="fa fa-phone text-xs text-gray-400 mt-1"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">Phone</p>
                                        <p class="text-gray-900 font-medium">{{ $company->labor_consultant_phone ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-2">
                                    <i class="fa fa-envelope text-xs text-gray-400 mt-1"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">{{ __('lang.email') }}</p>
                                        <p class="text-gray-900 font-medium break-all">{{ $company->labor_consultant_email ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selected Contacts Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fa fa-at"></i>
                            {{ __('lang.selected_contacts') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        @if($company->contacts && is_array($company->contacts))
                            <div class="space-y-2">
                                @foreach($company->contacts as $contact)
                                    <div class="flex items-center gap-2 bg-indigo-50 text-indigo-700 px-4 py-3 rounded-lg">
                                        <i class="fa fa-envelope text-sm"></i>
                                        <span class="text-sm font-medium break-all">{{ $contact }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fa fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500 text-sm">{{ __('lang.no_contacts_selected') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
