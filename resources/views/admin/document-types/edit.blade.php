@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Edit Document Type</h1>
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

        <form action="{{ route('admin.document-types.update', $documentType->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

                <!-- Row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="Enter document type name" value="{{ old('name', $documentType->name) }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Validity Year <span class="text-red-500">*</span></label>
                        <input type="number" name="validity_year" placeholder="Enter validity years" value="{{ old('validity_year', $documentType->validity_year) }}" min="1" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Notes</label>
                    <textarea name="notes" placeholder="Enter notes" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes', $documentType->notes) }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('admin.document-types.index') }}"
                        class="bg-gray-500 text-white px-6 py-2.5 rounded-lg hover:bg-gray-600">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-blue-700 text-white px-6 py-2.5 rounded-lg hover:bg-blue-800">
                        Update
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
