@extends('layouts.app')

@section('content')

    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flash-message-box" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative flash-message-box" role="alert">
            <span class="block sm:inline">{!! session('error') !!}</span>
        </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.companies') }}</h1>

        <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <a href="{{ route('admin.companies.export') }}"
                class=" px-5 py-3 font-semibold text-gray-500 hover:text-[#0C3183] hover:bg-blue-50 text-sm border-r border-gray-200 flex" title="{{ __('lang.export_data') }}">
                <i class="text-gray-500 fa fa-download"></i>
            </a>
            <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                class=" px-5 py-3 font-semibold text-gray-500 hover:text-[#0C3183] hover:bg-blue-50 text-sm border-r border-gray-200 flex" title="{{ __('lang.import_data') }}">
                <i class="text-gray-500 fa fa-upload"></i>
            </button>
            <a href="{{ route('admin.companies.create') }}"
                class=" px-5 py-3 font-semibold text-gray-500 hover:text-[#0C3183] hover:bg-blue-50 text-sm flex" title="{{ __('lang.create_company') }}">
                <i class="text-gray-500 fa fa-plus"></i>
            </a>
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('lang.import_companies') }}</h3>
                <button onclick="document.getElementById('importModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <form action="{{ route('admin.companies.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.select_excel_file') }}</label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183]">
                    <p class="text-xs text-gray-500 mt-2">{{ __('lang.supported_formats') }}</p>
                </div>

                <div class="mb-4">
                    <a href="{{ route('admin.companies.template') }}"
                        class="text-[#0C3183] hover:underline text-sm flex items-center gap-2">
                        <i class="fa fa-download"></i>
                        {{ __('lang.download_import_template') }}
                    </a>
                </div>

                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="bg-gray-500 text-white px-6 py-2.5 rounded-lg hover:bg-gray-600">
                        {{ __('lang.cancel') }}
                    </button>
                    <button type="submit"
                        class="bg-[#0C3183] text-white px-6 py-2.5 rounded-lg hover:bg-[#0a2766]">
                        {{ __('lang.import') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 bg-white">
            <thead class="text-xs text-gray-900 uppercase bg-white border-b">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        {{ __('lang.id') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ strtoupper(__('lang.company_name')) }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ strtoupper(__('lang.vat_number')) }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ strtoupper(__('lang.tax_id_code')) }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ strtoupper(__('lang.actions')) }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($companies->count() > 0)
                    @foreach ($companies as $company)
                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-500 whitespace-nowrap">
                                {{ $company->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $company->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $company->vat_number }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $company->tax_code}}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.companies.show', $company->id) }}"
                                    class="font-medium text-gray-500 p-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="{{ __('lang.view') }}">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.companies.edit', $company->id) }}"
                                    class="font-medium text-gray-500 p-2 ml-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="{{ __('lang.edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                {{-- <a href="#"
                                    class="font-medium text-red-500 p-2 ml-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]">
                                    <i class="fa fa-trash"></i>
                                </a> --}}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="bg-white border-b border-gray-200">
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            {{ __('lang.no_data_available') }}
                        </td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>

      <div class="mt-6 flex justify-end">
        {{ $companies->onEachSide(1)->links('pagination::tailwind') }}
    </div>


@endsection
