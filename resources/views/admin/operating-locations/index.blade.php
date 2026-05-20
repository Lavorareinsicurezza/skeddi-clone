@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-6">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.operating_locations') }}</h1>
                    <p class="text-gray-500 text-sm flex items-center gap-2 mt-1">
                        <i class="fa fa-map-marker-alt text-[#0C3183]"></i>
                        <span>{{ __('lang.manage_operating_locations') }}</span>
                    </p>
                </div>
                <div class="flex gap-2">
                    @can('view operating-locations')
                    <a href="{{ route('admin.operating-locations.export') }}"
                        class="bg-white text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 shadow-sm border border-gray-200 transition-all flex items-center gap-2 text-sm">
                        <i class="fa fa-download"></i>
                        <span>{{ __('lang.export') }}</span>
                    </a>
                    @endcan
                    @can('create operating-locations')
                    <a href="{{ route('admin.operating-locations.create') }}"
                        class="bg-[#0C3183] text-white px-4 py-2 rounded-lg hover:bg-blue-800 shadow-sm transition-all flex items-center gap-2 text-sm">
                        <i class="fa fa-plus"></i>
                        <span>{{ __('lang.add_operating_location') }}</span>
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <i class="fa fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <i class="fa fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Operating Locations Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('lang.name') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('lang.company') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('lang.address') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('lang.site_contact') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('lang.status') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('lang.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($operatingLocations as $location)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $location->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $location->company->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $location->full_address ?: '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $location->site_contact_name ?: '—' }}
                                    </div>
                                    @if($location->site_contact_phone)
                                        <div class="text-xs text-gray-500">
                                            {{ $location->site_contact_phone }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($location->is_active)
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">
                                            {{ __('lang.active') }}
                                        </span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">
                                            {{ __('lang.inactive') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        @can('view operating-locations')
                                        <a href="{{ route('admin.operating-locations.show', $location->id) }}"
                                            class="text-blue-600 hover:text-blue-900"
                                            title="{{ __('lang.view') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @endcan

                                        @can('edit operating-locations')
                                        <a href="{{ route('admin.operating-locations.edit', $location->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900"
                                            title="{{ __('lang.edit') }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @endcan

                                        @can('delete operating-locations')
                                        <form action="{{ route('admin.operating-locations.destroy', $location->id) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('{{ __('lang.confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900"
                                                    title="{{ __('lang.delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fa fa-map-marker-alt text-4xl text-gray-300 mb-2"></i>
                                    <p>{{ __('lang.no_operating_locations_found') }}</p>
                                    <p class="text-sm mt-1">{{ __('lang.add_first_operating_location') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($operatingLocations->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $operatingLocations->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
