@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('lang.edit_user') }}</h1>
            <a href="{{ route('admin.users.index') }}" class="text-blue-700 text-sm flex items-center gap-2">
                <i class="fa fa-arrow-left"></i>
                {{ __('lang.back_to_list') }}
            </a>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

                @if ($errors->any())
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">{{ __('lang.please_fix_following_errors') }}</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('lang.email_address') }}</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            placeholder="{{ __('lang.enter_email') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('lang.role') }}</label>
                        <select name="role"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role') border-red-500 @enderror">
                            <option value="">{{ __('lang.select_role') }}</option>
                            @foreach ($roles as $roleName)
                                <option value="{{ $roleName }}" {{ old('role', $user->role) == $roleName ? 'selected' : '' }}>
                                    {{ ucfirst($roleName) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit -->
                <div class="text-end">
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-8 py-3 rounded-lg hover:bg-gray-600 transition-colors mr-2 inline-block">{{ __('lang.cancel') }}</a>
                    <button type="submit"
                        class="bg-blue-700 text-white px-8 py-3 rounded-lg hover:bg-blue-800 transition-colors">
                        {{ __('lang.save') }}
                    </button>
                </div>

            </div>
        </form>
    </div>
@endsection
