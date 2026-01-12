@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-6">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $operatingLocation->name }}</h1>
                    <p class="text-gray-500 text-sm flex items-center gap-2 mt-1">
                        <i class="fa fa-map-marker-alt text-[#0C3183]"></i>
                        <span>{{ __('lang.operating_location_details') }}</span>
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.operating-locations.index') }}"
                        class="bg-white text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 shadow-sm border border-gray-200 transition-all flex items-center gap-2 text-sm">
                        <i class="fa fa-arrow-left"></i>
                        <span>{{ __('lang.back') }}</span>
                    </a>
                    @can('edit operating-locations')
                    <a href="{{ route('admin.operating-locations.edit', $operatingLocation->id) }}"
                        class="bg-[#0C3183] text-white px-4 py-2 rounded-lg hover:bg-blue-800 shadow-sm transition-all flex items-center gap-2 text-sm">
                        <i class="fa fa-edit"></i>
                        <span>{{ __('lang.edit') }}</span>
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Location Details Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">{{ __('lang.location_information') }}</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Company Information -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">{{ __('lang.company') }}</h3>
                        <p class="text-gray-900">{{ $operatingLocation->company->name }}</p>
                        <p class="text-sm text-gray-600">{{ __('lang.vat_number') }}: {{ $operatingLocation->company->vat_number }}</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">{{ __('lang.status') }}</h3>
                        @if($operatingLocation->is_active)
                            <span class="bg-green-100 text-green-800 text-sm font-semibold px-2 py-1 rounded-full">
                                {{ __('lang.active') }}
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-800 text-sm font-semibold px-2 py-1 rounded-full">
                                {{ __('lang.inactive') }}
                            </span>
                        @endif
                    </div>

                    <!-- Address Information -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">{{ __('lang.address') }}</h3>
                        @if($operatingLocation->full_address)
                            <p class="text-gray-900">{{ $operatingLocation->full_address }}</p>
                        @else
                            <p class="text-gray-500 italic">{{ __('lang.no_address_provided') }}</p>
                        @endif
                    </div>

                    <!-- Site Contact -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">{{ __('lang.site_contact') }}</h3>
                        @if($operatingLocation->site_contact_name)
                            <p class="text-gray-900">{{ $operatingLocation->site_contact_name }}</p>
                            @if($operatingLocation->site_contact_phone)
                                <p class="text-sm text-gray-600">{{ $operatingLocation->site_contact_phone }}</p>
                            @endif
                            @if($operatingLocation->site_contact_email)
                                <p class="text-sm text-gray-600">{{ $operatingLocation->site_contact_email }}</p>
                            @endif
                        @else
                            <p class="text-gray-500 italic">{{ __('lang.no_contact_provided') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa fa-users text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{ __('lang.employees') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $operatingLocation->workers()->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa fa-file-alt text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{ __('lang.documents') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $operatingLocation->documents()->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa fa-calendar text-2xl text-orange-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">{{ __('lang.created_at') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $operatingLocation->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Employees -->
        @if($operatingLocation->workers()->exists())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">{{ __('lang.employees_at_location') }}</h2>
                <a href="{{ route('admin.company-workers.index') }}?location_id={{ $operatingLocation->id }}"
                   class="text-[#0C3183] hover:text-blue-800 text-sm font-medium">
                    {{ __('lang.view_all') }}
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('lang.name') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('lang.job_title') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('lang.department') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('lang.status') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($operatingLocation->workers()->take(5)->get() as $worker)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $worker->first_name }} {{ $worker->surname }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $worker->job_title ?: '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $worker->department ?: '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($worker->is_active)
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">
                                            {{ __('lang.active') }}
                                        </span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">
                                            {{ __('lang.inactive') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Recent Documents -->
        @if($operatingLocation->documents()->exists())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">{{ __('lang.location_documents') }}</h2>
                <a href="{{ route('admin.documents.index') }}?location_id={{ $operatingLocation->id }}"
                   class="text-[#0C3183] hover:text-blue-800 text-sm font-medium">
                    {{ __('lang.view_all') }}
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('lang.document_name') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('lang.document_type') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('lang.expiration_date') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('lang.status') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($operatingLocation->documents()->take(5)->get() as $document)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $document->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $document->documentType->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $document->expiration_date ? $document->expiration_date->format('d/m/Y') : '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($document->expiration_date && $document->expiration_date->isPast())
                                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded-full">
                                            {{ __('lang.expired') }}
                                        </span>
                                    @elseif($document->expiration_date && $document->expiration_date->diffInDays() <= 30)
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full">
                                            {{ __('lang.expiring_soon') }}
                                        </span>
                                    @else
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">
                                            {{ __('lang.valid') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
@endsection
