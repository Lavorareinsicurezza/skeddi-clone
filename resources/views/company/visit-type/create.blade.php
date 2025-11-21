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
            {{ isset($companyVisitType) ? $companyVisitType->name : __('lang.create_visit_type') }}
        </h1>

    </div>

    <form action="{{ isset($companyVisitType) ? route('admin.company-visit-types.update', $companyVisitType->id) : route('admin.company-visit-types.store') }}"
          method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @if(isset($companyVisitType))
            @method('PUT')
        @endif

        <!-- Visit Type (from global visit_types table) -->
        <div class="mb-6">
            <label for="visit_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('lang.visit_type') }} <span class="text-red-500">*</span>
            </label>
            <select name="visit_type_id" id="visit_type_id"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                    required>
                <option value="">{{ __('lang.select_visit_type') }}</option>
                @foreach ($visitTypes as $visitType)
                    <option value="{{ $visitType->id }}"
                        {{ old('visit_type_id', $companyVisitType->visit_type_id ?? '') == $visitType->id ? 'selected' : '' }}>
                        {{ $visitType->name }} <!-- assuming global visit_types has a 'name' column -->
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Name & Specific Name -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('lang.name') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name"
                       value="{{ old('name', $companyVisitType->name ?? '') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                       placeholder="{{ __('lang.name') }}" required>
            </div>

            <div>
                <label for="specific_name" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('lang.specific_name') }}
                </label>
                <input type="text" name="specific_name" id="specific_name"
                       value="{{ old('specific_name', $companyVisitType->specific_name ?? '') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                       placeholder="{{ __('lang.specific_name') }}">
            </div>
        </div>

        <!-- Expiry Date -->
        <div class="mb-6">
            <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('lang.expiry_date') }}
            </label>
            <input type="date" name="expiry_date" id="expiry_date"
                   value="{{ old('expiry_date', $companyVisitType->expiry_date ?? '') }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm">
        </div>

        <!-- Notes -->
        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('lang.notes') }}
            </label>
            <textarea name="notes" id="notes" rows="4"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                      placeholder="{{ __('lang.notes') }}">{{ old('notes', $companyVisitType->notes ?? '') }}</textarea>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.company-visit-types.index') }}"
               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                {{ __('lang.cancel') }}
            </a>
            <button type="submit"
                    class="px-6 py-3 bg-[#0C3183] text-white rounded-lg hover:bg-[#0A2869] font-medium">
                {{ isset($companyVisitType) ? __('lang.update') : __('lang.create') }}
            </button>
        </div>
    </form>
@endsection
