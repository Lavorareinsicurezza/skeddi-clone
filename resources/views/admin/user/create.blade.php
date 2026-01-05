@extends('layouts.app')

@section('content')
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #e5e7eb;
            width: 50px;
            /* gray-200 */
            border-radius: 9999px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #0a2c82;
            /* dark blue */
            border-radius: 9999px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #001b5c;
            /* darker blue */
        }
    </style>

    <div class="container mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('lang.create_user') }}</h1>
            <a href="{{ route('admin.users.index') }}" class="text-[#0C3183] text-sm flex items-center gap-2">
                <i class="fa fa-arrow-left"></i>
                {{ __('lang.back_to_list') }}
            </a>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST">
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

                <!-- Email and Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">{{ __('lang.email_address') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            placeholder="{{ __('lang.enter_email') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">{{ __('lang.password') }}</label>
                        <input type="password" name="password"
                            placeholder="{{ __('lang.enter_password') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Role Selection -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-900 mb-2">{{ __('lang.role') }}</label>
                    <select name="role"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:border-transparent @error('role') border-red-500 @enderror">
                        <option value=""> {{ __('lang.select_role') }} </option>
                        @isset($roles)
                            @foreach ($roles as $roleName)
                                <option value="{{ $roleName }}" {{ old('role') == $roleName ? 'selected' : '' }}>{{ $roleName }}</option>
                            @endforeach
                        @endisset
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Functions Section -->
                <div class="mb-8">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">{{ __('lang.functions') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Company & RSVP -->
                        <div>
                            <label
                                class="border border-gray-300 rounded-lg px-4 py-3 flex items-start justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50 h-full">
                                <div class="flex-1">
                                    <span
                                        class="text-sm font-medium text-gray-900 block">{{ __('lang.company_rsvp') }}</span>
                                    <span
                                        class="text-xs text-gray-500 mt-1 block">{{ __('lang.permission_modify_company_rsvp') }}</span>
                                </div>
                                <input type="checkbox" name="functions[]" value="company_rsvp"
                                    {{ in_array('company_rsvp', old('functions', [])) ? 'checked' : '' }}
                                    class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183]  flex-shrink-0 mt-1">
                            </label>
                        </div>

                        <!-- Training Plan -->
                        <div>
                            <label
                                class="border border-gray-300 rounded-lg px-4 py-3 flex items-start justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50 h-full">
                                <div class="flex-1">
                                    <span
                                        class="text-sm font-medium text-gray-900 block">{{ __('lang.training_plan') }}</span>
                                    <span
                                        class="text-xs text-gray-500 mt-1 block">{{ __('lang.training_plan_access') }}</span>
                                </div>
                                <input type="checkbox" name="functions[]" value="training_plan"
                                    {{ in_array('training_plan', old('functions', [])) ? 'checked' : '' }}
                                    class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183]  flex-shrink-0 mt-1">
                            </label>
                        </div>

                        <!-- Health Surveillance -->
                        <div>
                            <label
                                class="border border-gray-300 rounded-lg px-4 py-3 flex items-start justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50 h-full">
                                <div class="flex-1">
                                    <span
                                        class="text-sm font-medium text-gray-900 block">{{ __('lang.health_surveillance') }}</span>
                                    <span
                                        class="text-xs text-gray-500 mt-1 block">{{ __('lang.health_surveillance_access') }}</span>
                                </div>
                                <input type="checkbox" name="functions[]" value="health_surveillance"
                                    {{ in_array('health_surveillance', old('functions', [])) ? 'checked' : '' }}
                                    class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183]  flex-shrink-0 mt-1">
                            </label>
                        </div>

                        <!-- Workers -->
                        <div>
                            <label
                                class="border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50 h-full">
                                <span class="text-sm font-medium text-gray-900">{{ __('lang.workers') }}</span>
                                <input type="checkbox" name="functions[]" value="workers"
                                    {{ in_array('workers', old('functions', [])) ? 'checked' : '' }}
                                    class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] ">
                            </label>
                        </div>

                        <!-- Documents -->
                        <div>
                            <label
                                class="border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50 h-full">
                                <span class="text-sm font-medium text-gray-900">{{ __('lang.documents') }}</span>
                                <input type="checkbox" name="functions[]" value="documents"
                                    {{ in_array('documents', old('functions', [])) ? 'checked' : '' }}
                                    class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] ">
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Visible Companies and Administration Functions Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Visible Companies -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">{{ __('lang.visible_companies') }} <span
                                class="text-red-500">*</span></h3>
                        <div
                            class="border @error('visible_company') border-red-500 @else border-gray-300 @enderror rounded-lg p-4">
                            <div class="mb-3">
                                <p class="w-full px-3 text-sm ">
                                    {{ __('lang.near') }}
                                </p>
                            </div>
                            <div class="h-[10rem] overflow-y-auto space-y-1 custom-scrollbar">
                                @foreach ($companies as $company)
                                    <label
                                        class="flex items-center gap-3 p-2">
                                            <p class="w-4 h-4 appearance-none bg-white border-[3px] border-gray-400 rounded-full ">
                                                </p>
                                        <span class="text-sm text-gray-900">{{ $company->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @error('visible_company')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Administration Functions -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">{{ __('lang.administration_functions') }}</h3>
                        <div class="space-y-4">
                            <label
                                class="border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50">
                                <span class="text-sm font-medium text-gray-900">{{ __('lang.content_management') }}</span>
                                <input type="checkbox" name="admin_functions[]" value="content_management"
                                    {{ in_array('content_management', old('admin_functions', [])) ? 'checked' : '' }}
                                    class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] ">
                            </label>
                            <label
                                class="border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-blue-50">
                                <span class="text-sm font-medium text-gray-900">{{ __('lang.users') }}</span>
                                <input type="checkbox" name="admin_functions[]" value="users"
                                    {{ in_array('users', old('admin_functions', [])) ? 'checked' : '' }}
                                    class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] ">
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-end">
                    <button type="submit"
                        class="bg-[#0C3183] text-white px-8 py-3 rounded-lg hover:bg-[#0a2766] transition-colors">
                        {{ __('lang.save') }}
                    </button>
                </div>

            </div>
        </form>
    </div>
@endsection
