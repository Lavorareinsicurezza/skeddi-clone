@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Visit Type Details</h1>
            <a href="{{ route('admin.visit-types.index') }}"
                class="bg-gray-500 text-white px-6 py-2.5 rounded-lg hover:bg-gray-600">
                Back to List
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Name</label>
                    <p class="text-gray-900 text-base">{{ $visitType->name }}</p>
                </div>

                <!-- Validity Year -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Validity Year</label>
                    <p class="text-gray-900 text-base">{{ $visitType->validity_year }}</p>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500 mb-2">Notes</label>
                    <p class="text-gray-900 text-base">{{ $visitType->notes ?? 'N/A' }}</p>
                </div>

                <!-- Created At -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Created At</label>
                    <p class="text-gray-900 text-base">{{ $visitType->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <!-- Updated At -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Updated At</label>
                    <p class="text-gray-900 text-base">{{ $visitType->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex gap-3 justify-end">
                @can('edit visit-types')
                <a href="{{ route('admin.visit-types.edit', $visitType->id) }}"
                    class="bg-[#0C3183] text-white px-6 py-2.5 rounded-lg hover:bg-[#0a2766]">
                    Edit
                </a>
                @endcan
                @can('delete visit-types')
                <form action="{{ route('admin.visit-types.destroy', $visitType->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this visit type?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-500 text-white px-6 py-2.5 rounded-lg hover:bg-red-600">
                        Delete
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>
@endsection
