@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">
                {{ isset($document) ? __('lang.edit_document') : __('lang.create_document') }}
            </h1>
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

        <form
            action="{{ isset($document) ? route('admin.company-documents.update', $document) : route('admin.company-documents.store') }}"
            method="POST">
            @csrf
            @if(isset($document)) @method('PUT') @endif

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

                <!-- Row 1: Nome + Tipo di documento -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('lang.name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $document->name ?? '') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0C3183]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('lang.document_type') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="document_type_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0C3183]"
                            required>
                            <option value="">Select Document Type</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}" {{ old('document_type_id', $document->document_type_id ?? '') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Row 2: Nota di programmazione + Data di scadenza -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('lang.scheduling_note') }}
                        </label>
                        <input type="text" name="scheduling_note"
                            value="{{ old('scheduling_note', $document->scheduling_note ?? '') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0C3183]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('lang.expiration_date') }}
                        </label>
                        <input type="date" name="expiration_date"
                            value="{{ old('expiration_date', $document->expiration_date ?? '') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0C3183]">
                    </div>
                </div>

                <!-- Da programmare toggle -->
                <div class="mb-6">
                    <label
                        class="border xl:w-[20%] lg:w-[30%] md:w-[40%] sm:w-[100%] border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50">
                        <span class="text-sm font-medium text-gray-800">{{ __('lang.to_schedule') }}</span>
                        <input type="checkbox" name="to_schedule" value="1" {{ old('to_schedule', $document->to_schedule ?? false) ? 'checked' : '' }}
                            class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:ring-offset-2">
                    </label>
                </div>

                <!-- Note -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.notes') }}</label>
                    <textarea name="notes" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0C3183]">{{ old('notes', $document->notes ?? '') }}</textarea>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 justify-end">
                    <a href="{{ route('admin.company-documents.index') }}"
                        class="px-6 py-2.5 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        {{ __('lang.cancel') }}
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#0C3183] text-white rounded-lg hover:bg-[#0a2766]">
                        {{ isset($document) ? __('lang.save_changes') : __('lang.create') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
