@extends('layouts.app')

@section('content')



    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.renewals') }}</h1>

    </div>

    <!-- Course Types Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">

                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.id') }}
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.courses') }}
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.managed_by') }}
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.subject') }}
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.previous_expiry_date') }}
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.course_update_date') }}
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.new_expiry_date') }}
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.renewal_operation_date') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($renewals as $log)
                <tr>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $log->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $log->companyCourseType->name ?? '—' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $log->managed_by }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $log->subject }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $log->previous_expiry_date?->format('d/m/Y') ?? '—' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $log->course_update_date?->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                        {{ $log->new_expiry_date?->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $log->renewal_operation_date?->format('d/m/Y') }}
                    </td>
                </tr>

                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            {{ __('lang.no_data_available') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
