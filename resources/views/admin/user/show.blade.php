@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $user->name }}</h1>
                    <p class="text-gray-900 flex items-center gap-2">
                        <i class="fa fa-user text-blue-700"></i>
                        <span>{{ __('lang.user_information') }}</span>
                    </p>
                </div>
                <div class="flex gap-3">
                    @can('edit users')
                    <a href="{{ route('admin.users.edit', $user->id) }}"
                        class="bg-blue-700 text-white px-6 py-3 rounded-lg hover:bg-blue-800 shadow-sm transition-all flex items-center gap-2">
                        <i class="fa fa-edit"></i>
                        <span>{{ __('lang.edit') }}</span>
                    </a>
                    @endcan
                    <a href="{{ route('admin.users.index') }}"
                        class="bg-white text-gray-900 px-6 py-3 rounded-lg hover:bg-gray-50 shadow-sm border border-gray-200 transition-all flex items-center gap-2">
                        <i class="fa fa-arrow-left"></i>
                        <span>{{ __('lang.back') }}</span>
                    </a>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="flex gap-3 mt-4">
                <span
                    class="bg-blue-100 text-blue-800 text-xs font-bold px-4 py-2 rounded-full flex items-center gap-2">
                    <i class="fa fa-user-tag"></i>
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Column - Main Information -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Basic Information Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-[#0C3183] to-blue-700 px-6 py-4">
                        <h2 class="text-lg font-bold flex items-center gap-2">
                            <i class="fa fa-info-circle"></i>
                            {{ __('lang.basic_information') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-start gap-3">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <i class="fa fa-envelope text-blue-700"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-900 uppercase font-bold mb-1">
                                        {{ __('lang.email_address') }}</p>
                                    <p class="text-gray-900 font-bold break-all">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <i class="fa fa-user-tie text-blue-700"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-900 uppercase font-bold mb-1">{{ __('lang.role') }}</p>
                                    <p class="text-gray-900 font-bold">{{ ucfirst($user->role) }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <i class="fa fa-building text-blue-700"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-900 uppercase font-bold mb-1">
                                        {{ __('lang.company_name') }}</p>
                                    <p class="text-gray-900 font-bold">{{ $user->company->name ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <i class="fa fa-calendar text-blue-700"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-900 uppercase font-bold mb-1">{{ __('lang.created_at') }}
                                    </p>
                                    <p class="text-gray-900 font-bold">{{ $user->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-[#0C3183] to-blue-700 px-6 py-4">
                        <h2 class="text-lg font-bold flex items-center gap-2">
                            <i class="fa fa-tasks"></i>
                            {{ __('lang.functions_permissions') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        @if ($userPermissions->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($userPermissions as $permission)
                                    <div class="flex items-center gap-2 bg-green-50 text-green-700 px-4 py-3 rounded-lg">
                                        <i class="fa fa-check-circle text-sm"></i>
                                        <span class="text-sm font-bold">{{ ucfirst($permission) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fa fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-900 text-sm">{{ __('lang.no_functions_assigned') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Right Column - Additional Information -->
            <div class="space-y-6">

                <!-- Role Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                        <h2 class="text-lg font-bold flex items-center gap-2">
                            <i class="fa fa-user-shield"></i>
                            {{ __('lang.role') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        @foreach ($user->roles as $role)
                            <div class="flex items-center gap-2 bg-indigo-50 text-indigo-700 px-4 py-3 rounded-lg mb-2">
                                <i class="fa fa-id-badge text-sm"></i>
                                <span class="text-sm font-bold">{{ ucfirst($role->name) }}</span>
                            </div>
                        @endforeach
                        @if ($user->roles->isEmpty())
                            <div class="text-center py-8">
                                <i class="fa fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-900 text-sm">{{ __('lang.no_functions_assigned') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
