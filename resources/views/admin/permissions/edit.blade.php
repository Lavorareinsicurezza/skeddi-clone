@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.edit_permission') }}</h1>
    </div>

    <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST" class="bg-white p-6 rounded-lg border border-gray-200">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.name') }}</label>
            <input type="text" name="name" value="{{ old('name', $permission->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">{{ __('lang.cancel') }}</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">{{ __('lang.update') }}</button>
        </div>
    </form>
@endsection
