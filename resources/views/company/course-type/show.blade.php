@extends('layouts.app')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">{{ $companyCourseType->name }}</h1>
        <div class="flex gap-3">
            @can('edit company-course-types')
            <a href="{{ route('admin.company-course-types.edit', $companyCourseType->id) }}"
                class="px-6 py-3 bg-[#0C3183] text-white rounded-lg hover:bg-[#0A2869] font-medium">
                {{ __('lang.edit') }}
            </a>
            @endcan
            <a href="{{ route('admin.company-course-types.index') }}"
                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                {{ __('lang.back') }}
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <!-- Course Type -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.course_type') }}</label>
            <p class="text-gray-900 text-lg">{{ $companyCourseType->courseType->course_name ?? '-' }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.name') }}</label>
                <p class="text-gray-900">{{ $companyCourseType->name }}</p>
            </div>

            <!-- Validity Years -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.validity_year') }}</label>
                <p class="text-gray-900">{{ $companyCourseType->validity_years ?? '-' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Generic Column Name -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.generic_column_name') }}</label>
                <p class="text-gray-900">{{ $companyCourseType->generic_column_name ?? '-' }}</p>
            </div>

            <!-- Expiration Column Name -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.expiration_column_name') }}</label>
                <p class="text-gray-900">{{ $companyCourseType->expiration_column_name ?? '-' }}</p>
            </div>
        </div>

        <!-- Generic -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.generic') }}</label>
            <p class="text-gray-900">
                @if($companyCourseType->is_generic)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ __('lang.yes') }}
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        {{ __('lang.no') }}
                    </span>
                @endif
            </p>
        </div>

        <!-- Notes -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.notes') }}</label>
            <p class="text-gray-900 whitespace-pre-wrap">{{ $companyCourseType->notes ?? '-' }}</p>
        </div>

        @if($companyCourseType->trainingPlanRecords->count() > 0)
            <!-- Upcoming Deadlines Section (Static) -->
            <div class="mb-6 bg-white">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('lang.upcoming_deadlines') }}</h3>

                <div class="space-y-3">
                    <!-- Deadline Rows -->
                    @foreach($companyCourseType->trainingPlanRecords as $record)
                        @php
                            $expDate = $record->expiration_date;
                            $now = \Carbon\Carbon::now();
                            $daysUntilExpiry = $now->diffInDays($expDate, false);

                            if ($daysUntilExpiry < 0) {
                                $colorClass = 'text-red-600'; // Expired
                            } elseif ($daysUntilExpiry <= 30) {
                                $colorClass = 'text-orange-500'; // Expiring soon
                            } else {
                                $colorClass = 'text-green-600'; // Valid
                            }
                        @endphp
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg bg-white">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="text-sm text-gray-900">
                                    <span>{{ date('d/m/Y', strtotime($record->training_date)) }}</span>
                                    <span>(
                                        {{ __('lang.expiration') }}
                                        <span
                                            class="{{ $colorClass }}">{{ date('d/m/Y', strtotime($record->expiration_date)) }}</span>
                                        )</span>
                                </div>
                                <div class="flex-1 text-sm text-gray-700 text-center">
                                    {{ $record->worker->department }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="pt-4 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-500">
                <div>
                    <span class="font-medium">{{ __('lang.created_at') }}:</span>
                    {{ $companyCourseType->created_at->format('d/m/Y H:i') }}
                </div>
                <div>
                    <span class="font-medium">{{ __('lang.update') }}:</span>
                    {{ $companyCourseType->updated_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>

@endsection
