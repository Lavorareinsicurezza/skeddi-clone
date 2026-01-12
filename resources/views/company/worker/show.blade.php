@extends('layouts.app')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.worker') }} {{ __('lang.view') }}</h1>
        <div class="flex gap-3">
            @can('edit company-workers')
                <a href="{{ route('admin.company-workers.edit', $worker->id) }}"
                    class="px-6 py-3 bg-[#0C3183] text-white rounded-lg hover:bg-[#0A2869] font-medium">
                    {{ __('lang.edit') }}
                </a>
            @endcan
            <a href="{{ route('admin.company-workers.index') }}"
                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                {{ __('lang.back') }}
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.first_name') }}</label>
                <p class="text-gray-900 text-lg">{{ $worker->first_name }}</p>
            </div>

            <!-- Surname -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.surname') }}</label>
                <p class="text-gray-900 text-lg">{{ $worker->surname }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Operating Location -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.operating_location') }}</label>
                <p class="text-gray-900">{{ $worker->operatingLocation ? $worker->operatingLocation->name : '-' }}</p>
            </div>

            <!-- Job Title -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.job_title') }}</label>
                <p class="text-gray-900">{{ $worker->job_title ?? '-' }}</p>
            </div>
        </div>

        <!-- Workplace Safety Risk -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.workplace_safety_risk') }}</label>
            <p class="text-gray-900">
                @if ($worker->workplace_safety_risk)
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        {{ __('lang.yes') }}
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        {{ __('lang.no') }}
                    </span>
                @endif
            </p>
        </div>

        @if ($worker->workplace_safety_risk)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Workplace Safety Risk Note -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.add_note') }}</label>
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $worker->workplace_safety_risk_note ?? '-' }}</p>
                </div>

                <!-- Workplace Safety Risk Document -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.add_new_document') }}</label>
                    @if ($worker->workplace_safety_risk_document)
                        <a href="{{ asset('storage/' . $worker->workplace_safety_risk_document) }}" target="_blank"
                            class="inline-flex items-center gap-2 text-[#0C3183] hover:text-[#0A2869] font-medium">
                            <i class="fa fa-file-pdf"></i>
                            {{ basename($worker->workplace_safety_risk_document) }}
                            <i class="fa fa-external-link text-xs"></i>
                        </a>
                    @else
                        <p class="text-gray-900">-</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Status -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.active') }}</label>
            <p class="text-gray-900">
                @if ($worker->is_active)
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ __('lang.in_force') }}
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ __('lang.no_longer_in_force') }}
                    </span>
                @endif
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Additional Information -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.additional_information') }}</label>
                <p class="text-gray-900 whitespace-pre-wrap">{{ $worker->additional_information ?? '-' }}</p>
            </div>

            <!-- Worker Documentation -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.worker_documentation') }}</label>
                <p class="text-gray-900 whitespace-pre-wrap">{{ $worker->worker_documentation ?? '-' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- PPE -->
            {{-- <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.ppe') }}</label>
                <p class="text-gray-900 whitespace-pre-wrap">{{ $worker->ppe ?? '-' }}</p>
            </div> --}}

            <!-- Movement History -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.movement_history') }}</label>
                <p class="text-gray-900 whitespace-pre-wrap">{{ $worker->movement_history ?? '-' }}</p>
            </div>
        </div>

        <!-- Training Experience -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.training_experience') }}</label>
            <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                <p class="text-gray-900 whitespace-pre-wrap">{{ $worker->training_experience ?? '-' }}</p>
            </div>
        </div>

        <!-- Medical Visits -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-500 mb-1">{{ __('lang.medical_visits') }}</label>
            <p class="text-gray-900 whitespace-pre-wrap">{{ $worker->medical_visits ?? '-' }}</p>
        </div>

        <div class="pt-4 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-500">
                <div>
                    <span class="font-medium">{{ __('lang.created_at') }}:</span>
                    {{ $worker->created_at->format('d/m/Y H:i') }}
                </div>
                <div>
                    <span class="font-medium">{{ __('lang.update') }}:</span>
                    {{ $worker->updated_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>

@endsection
