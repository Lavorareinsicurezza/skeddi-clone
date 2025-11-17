@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.course_type_details') }}</h1>
            <a href="{{ route('admin.course-types.index') }}"
                class="bg-gray-500 text-white px-6 py-2.5 rounded-lg hover:bg-gray-600">
                {{ __('lang.back_to_list') }}
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Course Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">{{ __('lang.course_name') }}</label>
                    <p class="text-gray-900 text-base">{{ $courseType->course_name }}</p>
                </div>

                <!-- Validity Year -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">{{ __('lang.validity_year') }}</label>
                    <p class="text-gray-900 text-base">{{ $courseType->validity_year }}</p>
                </div>

                <!-- Generic -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">{{ __('lang.generic') }}</label>
                    <p class="text-gray-900 text-base">{{ $courseType->generic ?? 'N/A' }}</p>
                </div>

                <!-- Expiration -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">{{ __('lang.expiration') }}</label>
                    <p class="text-gray-900 text-base">{{ $courseType->expiration ? $courseType->expiration->format('d/m/Y') : 'N/A' }}</p>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500 mb-2">{{ __('lang.notes') }}</label>
                    <p class="text-gray-900 text-base">{{ $courseType->notes ?? 'N/A' }}</p>
                </div>

                <!-- Created At -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">{{ __('lang.created_at') }}</label>
                    <p class="text-gray-900 text-base">{{ $courseType->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <!-- Updated At -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">{{ __('lang.update') }}</label>
                    <p class="text-gray-900 text-base">{{ $courseType->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex gap-3 justify-end">
                <a href="{{ route('admin.course-types.edit', $courseType->id) }}"
                    class="bg-[#0C3183] text-white px-6 py-2.5 rounded-lg hover:bg-[#0a2766]">
                    {{ __('lang.edit') }}
                </a>
                <form action="{{ route('admin.course-types.destroy', $courseType->id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('lang.delete_course_type_confirm') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-500 text-white px-6 py-2.5 rounded-lg hover:bg-red-600">
                        {{ __('lang.actions') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
