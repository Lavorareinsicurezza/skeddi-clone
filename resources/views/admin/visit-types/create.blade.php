@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.visit_types_management') }}</h1>
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

        <form action="{{ route('admin.visit-types.store') }}" method="POST">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

                <!-- Row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="{{ __('lang.enter_visit_name') }}" value="{{ old('name') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.validity_year') }} <span class="text-red-500">*</span></label>
                        <input type="number" name="validity_year" placeholder="{{ __('lang.enter_validity_years') }}" value="{{ old('validity_year') }}" min="1" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.notes') }}</label>
                    <textarea name="notes" placeholder="{{ __('lang.enter_notes') }}" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent">{{ old('notes') }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('admin.visit-types.index') }}"
                        class="bg-gray-500 text-white px-6 py-2.5 rounded-lg hover:bg-gray-600">
                        {{ __('lang.cancel') }}
                    </a>
                    <button type="submit"
                        class="bg-[#0C3183] text-white px-6 py-2.5 rounded-lg hover:bg-[#0a2766]">
                        {{ __('lang.create') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
