@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.edit_course_type') }}</h1>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.course-types.update', $courseType->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

                <!-- Row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('lang.order') }}</label>
                        <input type="number" name="sort_order" min="1" value="{{ old('sort_order', $courseType->sort_order) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-xs text-gray-900 mt-1">{{ __('lang.sort_order_hint') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('lang.course_name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="course_name" placeholder="{{ __('lang.enter_course_name') }}" value="{{ old('course_name', $courseType->course_name) }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('lang.validity_year') }} <span class="text-red-500">*</span></label>
                        <input type="number" name="validity_year" placeholder="{{ __('lang.enter_validity_years') }}" value="{{ old('validity_year', $courseType->validity_year) }}" min="1" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Row 2 -->
                {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('lang.generic') }}</label>
                        <input type="text" name="generic" placeholder="{{ __('lang.enter_generic') }}" value="{{ old('generic', $courseType->generic) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('lang.expiration') }}</label>
                        <input type="date" name="expiration" value="{{ old('expiration', $courseType->expiration ? $courseType->expiration->format('Y-m-d') : '') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div> --}}

                <!-- Row 3 -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('lang.notes') }}</label>
                    <textarea name="notes" placeholder="{{ __('lang.enter_notes') }}" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes', $courseType->notes) }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('admin.course-types.index') }}"
                        class="bg-gray-500 text-white px-6 py-2.5 rounded-lg hover:bg-gray-600">
                        {{ __('lang.cancel') }}
                    </a>
                    <button type="submit"
                        class="bg-blue-700 text-white px-6 py-2.5 rounded-lg hover:bg-blue-800">
                        {{ __('lang.update') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
