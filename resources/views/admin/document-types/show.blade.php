@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.document_type_details') }}</h1>
            <a href="{{ route('admin.document-types.index') }}"
                class="bg-gray-500 text-white px-6 py-2.5 rounded-lg hover:bg-gray-600">
                {{ __('lang.back_to_list') }}
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('lang.name') }}</label>
                    <p class="text-gray-900 text-base">{{ $documentType->name }}</p>
                </div>

                <!-- Validity Year -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('lang.validity_year') }}</label>
                    <p class="text-gray-900 text-base">{{ $documentType->validity_year }}</p>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('lang.notes') }}</label>
                    <p class="text-gray-900 text-base">{{ $documentType->notes ?? 'N/A' }}</p>
                </div>

                <!-- Created At -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('lang.created_at') }}</label>
                    <p class="text-gray-900 text-base">{{ $documentType->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <!-- Updated At -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('lang.updated_at') }}</label>
                    <p class="text-gray-900 text-base">{{ $documentType->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex gap-3 justify-end">
                @can('edit document-types')
                <a href="{{ route('admin.document-types.edit', $documentType->id) }}"
                    class="bg-blue-700 text-white px-6 py-2.5 rounded-lg hover:bg-blue-800">
                    {{ __('lang.edit') }}
                </a>
                @endcan
                @can('delete document-types')
                <form action="{{ route('admin.document-types.destroy', $documentType->id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('lang.delete_document_type_confirm') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-500 text-white px-6 py-2.5 rounded-lg hover:bg-red-600">
                        {{ __('lang.delete') }}
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>
@endsection
