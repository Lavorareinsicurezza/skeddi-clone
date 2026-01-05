@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Role Details</h1>
    </div>

    <div class="bg-white p-6 rounded-lg border border-gray-200">
        <div class="mb-4">
            <span class="text-sm text-gray-500">{{ __('lang.name') }}</span>
            <div class="text-gray-900 font-semibold">{{ $role->name }}</div>
        </div>

        <div class="mb-4">
            <span class="text-sm text-gray-500">{{ __('lang.permissions') }}</span>
            <div class="mt-2">
                @foreach ($role->permissions as $permission)
                    <span class="inline-block px-2 py-1 mr-2 mb-2 bg-blue-50 text-[#0C3183] border border-blue-100 rounded">{{ $permission->name }}</span>
                @endforeach
            </div>
        </div>

        @can('edit roles')
        <div class="flex justify-end">
            <a href="{{ route('admin.roles.edit', $role->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Edit</a>
        </div>
        @endcan
    </div>
@endsection
