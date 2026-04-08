@extends('layouts.app')

@section('content')

    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">{{ __('lang.please_fix_following_errors') }}</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">
            {{ isset($companyCourseType) ? $companyCourseType->name : __('lang.create_course_type') }}
        </h1>

    </div>

    <form
        action="{{ isset($companyCourseType) ? route('admin.company-course-types.update', $companyCourseType->id) : route('admin.company-course-types.store') }}"
        method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @if (isset($companyCourseType))
            @method('PUT')
        @endif

        <!-- Course Type Dropdown -->
        <div class="mb-6">
            <label for="course_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('lang.course_types') }}
            </label>
            <select name="course_type_id" id="course_type_id"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                required>
                <option value="">{{ __('lang.select_course_type') }}</option>
                @foreach ($courseTypes as $courseType)
                    <option value="{{ $courseType->id }}" {{ old('course_type_id', $companyCourseType->course_type_id ?? '') == $courseType->id ? 'selected' : '' }}>
                        {{ $courseType->course_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Name and Validity Years (inherited from course type) -->
        @if(isset($companyCourseType))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('lang.name') }}
                </label>
                <input type="text" value="{{ $companyCourseType->courseType->course_name ?? $companyCourseType->name }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-sm" disabled>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('lang.validity_year') }}
                </label>
                <input type="text" value="{{ $companyCourseType->courseType->validity_year ?? $companyCourseType->validity_years }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-sm" disabled>
            </div>
        </div>
        @endif

        @if(isset($companyCourseType))
        <!-- Generic Column Name and Expiration Column Name -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="generic_column_name" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('lang.generic_column_name') }}
                </label>
                <input type="text" name="generic_column_name" id="generic_column_name"
                    value="{{ old('generic_column_name', $companyCourseType->generic_column_name ?? '') }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                    placeholder="{{ __('lang.generic_column_name') }}">
            </div>

            <div>
                <label for="expiration_column_name" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('lang.expiration_column_name') }}
                </label>
                <input type="text" name="expiration_column_name" id="expiration_column_name"
                    value="{{ old('expiration_column_name', $companyCourseType->expiration_column_name ?? '') }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                    placeholder="{{ __('lang.expiration_column_name') }}">
            </div>
        </div>

        <!-- Generic Checkbox -->
        <div class="mb-6">
            <label
                class="border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-[#EBF1FF] max-w-md">
                <span class="text-sm font-medium text-gray-800">{{ __('lang.generic') }}</span>
                <input type="checkbox" name="is_generic" id="is_generic" value="1" {{ old('is_generic', isset($companyCourseType) && $companyCourseType->is_generic ? true : false) ? 'checked' : '' }}
                    class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:ring-offset-2">
            </label>
        </div>

        <!-- Notes -->
        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('lang.notes') }}
            </label>
            <textarea name="notes" id="notes" rows="3"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                placeholder="{{ __('lang.notes') }}">{{ old('notes', $companyCourseType->notes ?? '') }}</textarea>
        </div>
        @endif

        <!-- Upcoming Deadlines Section (Static for now) -->
        @if(isset($companyCourseType) && $companyCourseType->trainingPlanRecords->count() > 0)

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

        <!-- Action Buttons -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.company-course-types.index') }}"
                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                {{ __('lang.cancel') }}
            </a>
            <button type="submit" class="px-6 py-3 bg-[#0C3183] text-white rounded-lg hover:bg-[#0A2869] font-medium">
                {{ isset($companyCourseType) ? __('lang.update') : __('lang.create') }}
            </button>
        </div>
    </form>

@endsection
