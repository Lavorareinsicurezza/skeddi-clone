@extends('layouts.app')

@section('content')

    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flash-message-box"
            role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative flash-message-box"
            role="alert">
            <span class="block sm:inline">{!! session('error') !!}</span>
        </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.workers') }}</h1>

        <div class="flex items-center gap-3">
            <!-- Filter Dropdown -->
            <div class="relative">
                {{-- <button id="filterDropdown" data-dropdown-toggle="filterDropdownMenu"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#0C3183] flex items-center gap-2"
                    type="button">
                    <i class="fa fa-filter text-gray-500"></i>
                    {{ __('lang.in_force') }}
                    <i class="fa fa-chevron-down text-xs text-gray-500"></i>
                </button> --}}

                {{-- <div id="filterDropdownMenu" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-44">
                    <ul class="py-2 text-sm text-gray-700">
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">{{ __('lang.all') }}</a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">{{ __('lang.in_force') }}</a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">{{ __('lang.inactive') }}</a>
                        </li>
                    </ul>
                </div> --}}
            </div>
            @can('view company-workers')
            <a href="{{ route('admin.company-workers.export') }}"
                class="px-5 py-3 font-semibold text-gray-500 hover:text-[#0C3183] hover:bg-[#EBF1FF] text-sm border border-gray-200 rounded-lg"
                title="{{ __('lang.export') }}">
                <i class="text-gray-500 fa fa-download"></i>
            </a>
            @endcan
            @can('create company-workers')
            <!-- Action Buttons -->
            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                    class="px-5 py-3 font-semibold text-gray-500 hover:text-[#0C3183] hover:bg-[#EBF1FF] text-sm border-r border-gray-200 flex"
                    title="{{ __('lang.import_data') }}">
                    <i class="text-gray-500 fa fa-upload"></i>
                </button>
                <a href="{{ route('admin.company-workers.create') }}"
                    class="px-5 py-3 font-semibold text-gray-500 hover:text-[#0C3183] hover:bg-[#EBF1FF] text-sm flex"
                    title="{{ __('lang.create') .' '. __('lang.worker') }}">
                    <i class="text-gray-500 fa fa-plus"></i>
                </a>
            </div>
            @endcan
        </div>
    </div>

    <!-- Workers Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.surname') }}
                    </th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.first_name') }}
                    </th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.operating_location') }}
                    </th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.position') }}
                    </th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($workers as $worker)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $worker->surname }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $worker->first_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $worker->operatingLocation ? $worker->operatingLocation->name : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <input type="text" value="{{ $worker->job_title }}" readonly
                                class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg w-full cursor-not-allowed">
                            {{-- <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-7/00 border border-gray-200">
                                {{ $worker->job_title ?? '-' }}
                            </span> --}}
                        </td>
                         <td class="px-6 py-4">
                            @can('view company-workers')
                                <a href="{{ route('admin.company-workers.show', $worker->id) }}"
                                    class="font-medium text-gray-500 p-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="{{ __('lang.view') }}">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                @endcan
                                @can('edit company-workers')
                                <a href="{{ route('admin.company-workers.edit', $worker->id) }}"
                                    class="font-medium text-gray-500 p-2 ml-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="{{ __('lang.edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endcan
                                @can('delete company-workers')
                                <form action="{{ route('admin.company-workers.destroy', $worker->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('{{ __('lang.delete_worker_confirm') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="font-medium text-red-500 p-2 hover:bg-red-50 border border-gray-200 rounded-[10px]"
                                        title="{{ __('lang.actions') }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                            {{ __('lang.no_data_available') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(isset($workers) && $workers->hasPages())
        <div class="mt-4 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                {{ __('lang.showing') }} {{ $workers->firstItem() }}-{{ $workers->lastItem() }} {{ __('lang.of') }} {{ $workers->total() }}
            </div>
            <div class="flex gap-2">
                @if ($workers->onFirstPage())
                    <span class="px-3 py-2 text-gray-400 cursor-not-allowed">
                        <i class="fa fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $workers->previousPageUrl() }}"
                        class="px-3 py-2 text-gray-600 hover:text-[#0C3183] hover:bg-[#EBF1FF] rounded transition-colors">
                        <i class="fa fa-chevron-left"></i>
                    </a>
                @endif

                @if ($workers->hasMorePages())
                    <a href="{{ $workers->nextPageUrl() }}"
                        class="px-3 py-2 text-gray-600 hover:text-[#0C3183] hover:bg-[#EBF1FF] rounded transition-colors">
                        <i class="fa fa-chevron-right"></i>
                    </a>
                @else
                    <span class="px-3 py-2 text-gray-400 cursor-not-allowed">
                        <i class="fa fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
    @endif

    <!-- Import Modal -->
    <div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">{{ __('lang.import_workers') }}</h3>
                <form action="{{ route('admin.company-workers.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.select_excel_file') }}</label>
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183]">
                        <p class="mt-1 text-xs text-gray-500">{{ __('lang.supported_formats') }}</p>
                    </div>
                    <div class="mb-4">
                        <a href="{{ route('admin.company-workers.template') }}"
                            class="text-sm text-[#0C3183] hover:underline flex items-center gap-1">
                            <i class="fa fa-download text-xs"></i>
                            {{ __('lang.download_import_template') }}
                        </a>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                            {{ __('lang.cancel') }}
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-[#0C3183] text-white rounded-md hover:bg-[#0A2869]">
                            {{ __('lang.import') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('styles')
    <style>
        /* Smooth transitions for interactive elements */
        button, a {
            transition: all 0.2s ease-in-out;
        }
    </style>
@endsection
