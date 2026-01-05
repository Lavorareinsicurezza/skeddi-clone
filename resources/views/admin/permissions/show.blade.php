@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Permission Details</h1>
    </div>

    <div class="bg-white p-6 rounded-lg border border-gray-200">
        <div class="mb-4">
            <span class="text-sm text-gray-500">Name</span>
            <div class="text-gray-900 font-semibold">{{ $permission->name }}</div>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Edit</a>
        </div>
    </div>
@endsection
