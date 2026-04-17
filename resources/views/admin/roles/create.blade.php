@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.create_role') }}</h1>
    </div>

    <form action="{{ route('admin.roles.store') }}" method="POST" class="bg-white p-6 rounded-lg border border-gray-200">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('lang.name') }}</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('lang.permissions') }}</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                @foreach ($permissions as $permission)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="permissions[]" {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }} value="{{ $permission->id }}" class="rounded">
                        <span class="text-gray-900 text-sm">{{ $permission->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('permissions')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 bg-gray-300 text-gray-900 rounded-md hover:bg-gray-400">{{ __('lang.cancel') }}</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">{{ __('lang.save') }}</button>
        </div>
    </form>
@endsection
