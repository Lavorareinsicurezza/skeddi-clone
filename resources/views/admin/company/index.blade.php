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
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.companies') }}</h1>

        <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <a href="{{ route('admin.companies.export') }}"
                class=" px-5 py-3 font-bold text-gray-900 hover:text-blue-700 hover:bg-blue-50 text-sm border-r border-gray-200 flex"
                title="{{ __('lang.export_data') }}">
                <i class="text-gray-900 fa fa-download"></i>
            </a>
            <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                class=" px-5 py-3 font-bold text-gray-900 hover:text-blue-700 hover:bg-blue-50 text-sm border-r border-gray-200 flex"
                title="{{ __('lang.import_data') }}">
                <i class="text-gray-900 fa fa-upload"></i>
            </button>
            @can('create companies')
            <a href="{{ route('admin.companies.create') }}"
                class=" px-5 py-3 font-bold text-gray-900 hover:text-blue-700 hover:bg-blue-50 text-sm flex"
                title="{{ __('lang.create_company') }}">
                <i class="text-gray-900 fa fa-plus"></i>
            </a>
            @endcan
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">{{ __('lang.import_companies') }}</h3>
                <button onclick="document.getElementById('importModal').classList.add('hidden')"
                    class="text-gray-900 hover:text-red-600">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <form action="{{ route('admin.companies.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('lang.select_excel_file') }}</label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-900 mt-2">{{ __('lang.supported_formats') }}</p>
                </div>

                <div class="mb-4">
                    <a href="{{ route('admin.companies.template') }}"
                        class="text-blue-700 hover:underline text-sm flex items-center gap-2">
                        <i class="fa fa-download"></i>
                        {{ __('lang.download_import_template') }}
                    </a>
                </div>

                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="bg-gray-500 text-white px-6 py-2.5 rounded-lg hover:bg-gray-600">
                        {{ __('lang.cancel') }}
                    </button>
                    <button type="submit" class="bg-blue-700 text-white px-6 py-2.5 rounded-lg hover:bg-blue-800">
                        {{ __('lang.import') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-900 bg-white">
            <thead class="text-xs text-white uppercase bg-blue-600 border-b">
                <tr class="border-b border-gray-200">
                    <th scope="col" class="px-6 py-3 font-bold">
                        {{ __('lang.id') }}
                    </th>
                    <th scope="col" class="px-6 py-3 font-bold">
                        {{ strtoupper(__('lang.company_name')) }}
                    </th>
                    <th scope="col" class="px-6 py-3 font-bold">
                        {{ __('lang.operating_locations') }}
                    </th>
                    <th scope="col" class="px-6 py-3 font-bold">
                        {{ strtoupper(__('lang.vat_number')) }}
                    </th>
                    <th scope="col" class="px-6 py-3 font-bold">
                        {{ strtoupper(__('lang.tax_id_code')) }}
                    </th>
                    <th scope="col" class="px-6 py-3 font-bold">
                        {{ strtoupper(__('lang.actions')) }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($companies->count() > 0)
                    @foreach ($companies as $company)
                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap">
                                {{ $company->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $company->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $company->operatingLocations()->count() }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $company->vat_number }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $company->tax_code }}
                            </td>
                            <td class="px-6 py-4">
                                @can('view companies')
                                <a href="{{ route('admin.companies.show', $company->id) }}"
                                    class="font-bold text-gray-900 p-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="{{ __('lang.view') }}">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                @endcan
                                @can('edit companies')
                                <a href="{{ route('admin.companies.edit', $company->id) }}"
                                    class="font-bold text-gray-900 p-2 ml-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="{{ __('lang.edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endcan
                                @can('delete companies')
                                <form action="{{ route('admin.companies.destroy', $company->id) }}" method="POST"
                                    class="inline-block ml-2"
                                    onsubmit="return confirm('{{ __('lang.delete_company_confirm') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="font-bold text-red-500 p-2 hover:bg-red-50 border border-gray-200 rounded-[10px]"
                                        title="{{ __('lang.actions') }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                                {{-- <a href="#"
                                    class="font-bold text-red-500 p-2 ml-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]">
                                    <i class="fa fa-trash"></i>
                                </a> --}}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="bg-white border-b border-gray-200">
                        <td colspan="5" class="px-6 py-4 text-center text-gray-900">
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

    @if (session('success') && session('success') == 'Company and all related data deleted successfully.')
        <script>
            localStorage.removeItem('selectedCompanyId');
            localStorage.removeItem('selectedCompanyName');
        </script>
    @endif
@endsection
